<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Finance;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@biztrack.com'],
            [
                'name' => 'Admin BizTrack',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'owner@biztrack.com'],
            [
                'name' => 'Owner Toko Kue Bu Nina',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'email_verified_at' => now(),
            ]
        );

        $categories = [
            'Kue Basah' => 'Aneka kue basah seperti bolu, lapis, dan brownies.',
            'Kue Kering' => 'Nastar, kastengel, putri salju dan kue kering lainnya.',
            'Bolu' => 'Bolu panggang, bolu kukus, dan variasinya.',
            'Roti' => 'Roti manis, roti sobek, roti tawar.',
            'Dessert' => 'Pudding, cheesecake, dan dessert box.',
        ];
        $catModels = [];
        foreach ($categories as $name => $desc) {
            $catModels[$name] = Category::updateOrCreate(['name' => $name], ['description' => $desc]);
        }

        $products = [
            ['name' => 'Bolu Coklat', 'category' => 'Bolu', 'cost_price' => 18000, 'selling_price' => 30000, 'stock' => 25, 'minimum_stock' => 5, 'unit' => 'pcs'],
            ['name' => 'Bolu Pandan', 'category' => 'Bolu', 'cost_price' => 17000, 'selling_price' => 28000, 'stock' => 20, 'minimum_stock' => 5, 'unit' => 'pcs'],
            ['name' => 'Brownies Coklat', 'category' => 'Kue Basah', 'cost_price' => 25000, 'selling_price' => 45000, 'stock' => 18, 'minimum_stock' => 5, 'unit' => 'box'],
            ['name' => 'Kue Lapis Legit', 'category' => 'Kue Basah', 'cost_price' => 55000, 'selling_price' => 95000, 'stock' => 8, 'minimum_stock' => 3, 'unit' => 'loyang'],
            ['name' => 'Nastar Premium', 'category' => 'Kue Kering', 'cost_price' => 48000, 'selling_price' => 85000, 'stock' => 15, 'minimum_stock' => 4, 'unit' => 'toples'],
            ['name' => 'Kastengel', 'category' => 'Kue Kering', 'cost_price' => 55000, 'selling_price' => 95000, 'stock' => 10, 'minimum_stock' => 4, 'unit' => 'toples'],
            ['name' => 'Donat Kentang', 'category' => 'Roti', 'cost_price' => 2500, 'selling_price' => 6000, 'stock' => 60, 'minimum_stock' => 15, 'unit' => 'pcs'],
            ['name' => 'Roti Sobek Coklat', 'category' => 'Roti', 'cost_price' => 14000, 'selling_price' => 28000, 'stock' => 18, 'minimum_stock' => 5, 'unit' => 'pcs'],
            ['name' => 'Cupcake Vanila', 'category' => 'Dessert', 'cost_price' => 5500, 'selling_price' => 12000, 'stock' => 40, 'minimum_stock' => 10, 'unit' => 'pcs'],
            ['name' => 'Pudding Coklat', 'category' => 'Dessert', 'cost_price' => 10000, 'selling_price' => 18000, 'stock' => 22, 'minimum_stock' => 6, 'unit' => 'cup'],
            ['name' => 'Risoles Mayo', 'category' => 'Kue Basah', 'cost_price' => 2500, 'selling_price' => 5000, 'stock' => 35, 'minimum_stock' => 10, 'unit' => 'pcs'],
            ['name' => 'Lemper Ayam', 'category' => 'Kue Basah', 'cost_price' => 2000, 'selling_price' => 4000, 'stock' => 30, 'minimum_stock' => 10, 'unit' => 'pcs'],
        ];

        $prodModels = [];
        foreach ($products as $i => $p) {
            $prodModels[] = Product::updateOrCreate(
                ['name' => $p['name']],
                [
                    'category_id' => $catModels[$p['category']]->id,
                    'code' => 'PRD-'.str_pad((string) ($i + 1), 5, '0', STR_PAD_LEFT),
                    'description' => 'Produk '.$p['name'].' dari Toko Kue Bu Nina.',
                    'cost_price' => $p['cost_price'],
                    'selling_price' => $p['selling_price'],
                    'stock' => $p['stock'],
                    'minimum_stock' => $p['minimum_stock'],
                    'unit' => $p['unit'],
                    'status' => 'active',
                ]
            );
        }

        if (Sale::count() === 0) {
            $this->seedSales($admin->id, $prodModels);
            $this->seedExpenses();
        }
    }

    /**
     * Seed 9 months of daily sales. Per-day transaction count follows a seasonal
     * pattern (weekends busier, year-end slightly busier) so time-series forecasting
     * produces interesting results.
     *
     * @param  array<int, Product>  $products
     */
    private function seedSales(int $userId, array $products): void
    {
        $start = Carbon::today()->subMonths(9)->startOfMonth();
        $end = Carbon::today();
        $cursor = $start->copy();

        while ($cursor <= $end) {
            $dow = $cursor->dayOfWeek; // 0 Sun … 6 Sat
            $monthBoost = in_array($cursor->month, [6, 7, 12], true) ? 1.4 : 1.0;
            $weekendBoost = in_array($dow, [0, 5, 6], true) ? 1.5 : 1.0;
            $txnCount = (int) round(rand(2, 5) * $weekendBoost * $monthBoost);

            for ($t = 0; $t < $txnCount; $t++) {
                $this->createDummySale($userId, $products, $cursor->copy());
            }
            $cursor->addDay();
        }
    }

    /**
     * @param  array<int, Product>  $products
     */
    private function createDummySale(int $userId, array $products, Carbon $date): void
    {
        $itemCount = rand(1, 4);
        shuffle($products);
        $items = array_slice($products, 0, $itemCount);

        $totalAmount = 0.0;
        $totalCost = 0.0;
        $rows = [];
        foreach ($items as $prod) {
            $qty = rand(1, 3);
            $price = (float) $prod->selling_price;
            $cost = (float) $prod->cost_price;
            $subtotal = $price * $qty;
            $costSubtotal = $cost * $qty;
            $totalAmount += $subtotal;
            $totalCost += $costSubtotal;
            $rows[] = compact('prod', 'qty', 'price', 'cost', 'subtotal', 'costSubtotal');
        }

        $method = ['cash', 'transfer', 'qris'][rand(0, 2)];
        $paid = $method === 'cash' ? ceil($totalAmount / 1000) * 1000 + rand(0, 10) * 1000 : $totalAmount;

        $sale = Sale::create([
            'invoice_number' => Sale::generateInvoiceNumber($date),
            'user_id' => $userId,
            'sale_date' => $date->toDateString(),
            'total_amount' => $totalAmount,
            'total_cost' => $totalCost,
            'gross_profit' => $totalAmount - $totalCost,
            'payment_method' => $method,
            'paid_amount' => $paid,
            'change_amount' => max(0, $paid - $totalAmount),
        ]);

        foreach ($rows as $r) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $r['prod']->id,
                'quantity' => $r['qty'],
                'price' => $r['price'],
                'cost_price' => $r['cost'],
                'subtotal' => $r['subtotal'],
                'profit' => $r['subtotal'] - $r['costSubtotal'],
            ]);
            StockMovement::create([
                'product_id' => $r['prod']->id,
                'type' => 'out',
                'quantity' => $r['qty'],
                'description' => 'Penjualan '.$sale->invoice_number,
                'movement_date' => $date->toDateString(),
            ]);
        }

        Finance::create([
            'type' => 'income',
            'category' => 'Penjualan',
            'description' => 'Pemasukan penjualan '.$sale->invoice_number,
            'amount' => $totalAmount,
            'transaction_date' => $date->toDateString(),
            'source' => 'sale',
            'reference_id' => $sale->id,
        ]);
    }

    private function seedExpenses(): void
    {
        $categories = [
            'Bahan Baku' => [80000, 450000],
            'Listrik' => [250000, 550000],
            'Air' => [80000, 180000],
            'Gaji' => [1200000, 2500000],
            'Transportasi' => [50000, 250000],
            'Sewa' => [1500000, 2500000],
            'Promosi' => [100000, 400000],
            'Peralatan' => [150000, 800000],
            'Lain-lain' => [50000, 300000],
        ];

        for ($m = 8; $m >= 0; $m--) {
            $base = Carbon::today()->subMonths($m)->startOfMonth();
            foreach ($categories as $cat => [$min, $max]) {
                $dateInMonth = $base->copy()->addDays(rand(0, $base->daysInMonth - 1));
                Finance::create([
                    'type' => 'expense',
                    'category' => $cat,
                    'description' => $cat.' bulan '.$base->translatedFormat('F Y'),
                    'amount' => rand($min, $max),
                    'transaction_date' => $dateInMonth->toDateString(),
                    'source' => 'manual',
                ]);
            }
        }
    }
}
