<?php

namespace App\Services;

use App\Models\Finance;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Repositories\SaleRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function __construct(
        private readonly SaleRepository $saleRepo,
        private readonly StockService $stockService,
    ) {}

    /**
     * Process a sale transaction.
     *
     * @param  array  $cart  Each item: ['product_id' => int, 'quantity' => int]
     * @param  array  $meta  ['payment_method' => string, 'paid_amount' => float, 'sale_date' => ?string]
     */
    public function process(array $cart, array $meta): Sale
    {
        if (empty($cart)) {
            throw new \DomainException('Keranjang masih kosong.');
        }

        $paymentMethod = $meta['payment_method'] ?? 'cash';
        $saleDate = $meta['sale_date'] ?? now()->toDateString();
        $userId = $meta['user_id'] ?? Auth::id();

        return DB::transaction(function () use ($cart, $paymentMethod, $saleDate, $userId, $meta) {
            $totalAmount = 0.0;
            $totalCost = 0.0;
            $grossProfit = 0.0;
            $preparedItems = [];

            foreach ($cart as $row) {
                /** @var Product $product */
                $product = Product::lockForUpdate()->findOrFail($row['product_id']);
                $qty = (int) $row['quantity'];
                if ($qty <= 0) {
                    throw new \DomainException("Jumlah produk {$product->name} tidak valid.");
                }
                $this->stockService->assertEnoughStock($product, $qty);

                $price = (float) $product->selling_price;
                $cost = (float) $product->cost_price;
                $subtotal = $price * $qty;
                $lineCost = $cost * $qty;
                $lineProfit = $subtotal - $lineCost;

                $totalAmount += $subtotal;
                $totalCost += $lineCost;
                $grossProfit += $lineProfit;

                $preparedItems[] = [
                    'product' => $product,
                    'quantity' => $qty,
                    'price' => $price,
                    'cost_price' => $cost,
                    'subtotal' => $subtotal,
                    'profit' => $lineProfit,
                ];
            }

            $paidAmount = (float) ($meta['paid_amount'] ?? $totalAmount);
            if ($paymentMethod === 'cash' && $paidAmount < $totalAmount) {
                throw new \DomainException('Uang yang dibayarkan kurang dari total pembayaran.');
            }
            $change = max(0, $paidAmount - $totalAmount);

            $sale = $this->saleRepo->createSale([
                'invoice_number' => Sale::generateInvoiceNumber(Carbon::parse($saleDate)),
                'user_id' => $userId,
                'sale_date' => $saleDate,
                'total_amount' => $totalAmount,
                'total_cost' => $totalCost,
                'gross_profit' => $grossProfit,
                'payment_method' => $paymentMethod,
                'paid_amount' => $paidAmount,
                'change_amount' => $change,
            ]);

            foreach ($preparedItems as $item) {
                /** @var Product $product */
                $product = $item['product'];
                $this->saleRepo->createItem([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'cost_price' => $item['cost_price'],
                    'subtotal' => $item['subtotal'],
                    'profit' => $item['profit'],
                ]);

                $product->decrement('stock', $item['quantity']);
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'description' => 'Penjualan '.$sale->invoice_number,
                    'movement_date' => $saleDate,
                ]);
            }

            Finance::create([
                'type' => 'income',
                'category' => 'Penjualan',
                'description' => 'Pemasukan penjualan '.$sale->invoice_number,
                'amount' => $totalAmount,
                'transaction_date' => $saleDate,
                'source' => 'sale',
                'reference_id' => $sale->id,
            ]);

            return $sale->fresh(['user', 'items.product']);
        });
    }

    public function delete(Sale $sale): bool
    {
        return DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'in',
                        'quantity' => $item->quantity,
                        'description' => 'Pembatalan penjualan '.$sale->invoice_number,
                        'movement_date' => now()->toDateString(),
                    ]);
                }
            }
            Finance::where('source', 'sale')->where('reference_id', $sale->id)->delete();

            return (bool) $this->saleRepo->delete($sale);
        });
    }
}
