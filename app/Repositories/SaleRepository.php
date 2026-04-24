<?php

namespace App\Repositories;

use App\Models\Finance;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SaleRepository
{
    public function paginate(?string $search = null, ?string $dateFrom = null, ?string $dateTo = null, int $perPage = 10): LengthAwarePaginator
    {
        return Sale::query()
            ->with(['user', 'details.product'])
            ->when($search, fn ($q) => $q->where('invoice_number', 'like', "%{$search}%"))
            ->when($dateFrom, fn ($q) => $q->whereDate('date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('date', '<=', $dateTo))
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    /**
     * Create a sale with details. $items is an array of [product_id, quantity].
     * Validates stock, reduces stock, records finance income - all in a DB transaction.
     */
    public function createSale(array $items, ?string $date = null): Sale
    {
        if (empty($items)) {
            throw new RuntimeException('Tidak ada produk pada transaksi.');
        }

        return DB::transaction(function () use ($items, $date) {
            $total = 0;
            $detailsData = [];

            foreach ($items as $item) {
                /** @var Product $product */
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $quantity = (int) $item['quantity'];

                if ($quantity < 1) {
                    throw new RuntimeException("Jumlah untuk produk {$product->name} tidak valid.");
                }
                if ($product->stock < $quantity) {
                    throw new RuntimeException("Stok produk {$product->name} tidak mencukupi (sisa {$product->stock}).");
                }

                $subtotal = (float) $product->price * $quantity;
                $total += $subtotal;

                $detailsData[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => (float) $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            $sale = Sale::create([
                'user_id' => Auth::id(),
                'invoice_number' => Sale::generateInvoiceNumber(),
                'total' => $total,
                'date' => $date ?: now()->toDateString(),
            ]);

            foreach ($detailsData as $d) {
                /** @var Product $product */
                $product = $d['product'];
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $d['quantity'],
                    'price' => $d['price'],
                    'subtotal' => $d['subtotal'],
                ]);
                $product->decrement('stock', $d['quantity']);
            }

            Finance::create([
                'type' => 'income',
                'amount' => $total,
                'description' => 'Pemasukan penjualan ' . $sale->invoice_number,
                'source' => 'Penjualan',
                'date' => $sale->date,
            ]);

            return $sale->fresh(['details.product', 'user']);
        });
    }

    public function deleteSale(Sale $sale): void
    {
        DB::transaction(function () use ($sale) {
            foreach ($sale->details as $detail) {
                $product = Product::find($detail->product_id);
                if ($product) {
                    $product->increment('stock', $detail->quantity);
                }
            }
            Finance::where('description', 'Pemasukan penjualan ' . $sale->invoice_number)
                ->where('type', 'income')
                ->delete();
            $sale->details()->delete();
            $sale->delete();
        });
    }
}
