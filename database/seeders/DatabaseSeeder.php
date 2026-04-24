<?php

namespace Database\Seeders;

use App\Models\Finance;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
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
        // --- Users ---
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

        // --- Products ---
        $products = [
            ['name' => 'Brownies',   'price' => 45000, 'stock' => 25, 'category' => 'Kue Basah'],
            ['name' => 'Bolu Kukus', 'price' => 25000, 'stock' => 40, 'category' => 'Kue Basah'],
            ['name' => 'Nastar',     'price' => 85000, 'stock' => 18, 'category' => 'Kue Kering'],
            ['name' => 'Kastengel',  'price' => 95000, 'stock' => 12, 'category' => 'Kue Kering'],
            ['name' => 'Donat',      'price' => 6000,  'stock' => 60, 'category' => 'Kue Goreng'],
            ['name' => 'Risoles',    'price' => 5000,  'stock' => 35, 'category' => 'Snack'],
            ['name' => 'Lemper',     'price' => 4000,  'stock' => 30, 'category' => 'Snack'],
            ['name' => 'Kue Lapis',  'price' => 30000, 'stock' => 3,  'category' => 'Kue Basah'],
            ['name' => 'Roti Sobek', 'price' => 28000, 'stock' => 20, 'category' => 'Roti'],
            ['name' => 'Cupcake',    'price' => 12000, 'stock' => 0,  'category' => 'Kue Basah'],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(
                ['name' => $p['name']],
                array_merge($p, ['status' => 'active'])
            );
        }

        // Avoid duplicating dummy sales on re-seed
        if (Sale::count() > 0) {
            return;
        }

        // --- Sales dummy untuk 30 hari terakhir ---
        $productModels = Product::all();
        for ($day = 29; $day >= 0; $day--) {
            $date = Carbon::today()->subDays($day);
            $numTxn = rand(1, 3);
            for ($t = 0; $t < $numTxn; $t++) {
                $items = $productModels->random(rand(1, 3));
                $total = 0;
                $sale = Sale::create([
                    'user_id' => $admin->id,
                    'invoice_number' => 'INV-' . $date->format('Ymd') . '-' . str_pad((string) ($t + 1), 4, '0', STR_PAD_LEFT) . '-' . rand(10, 99),
                    'total' => 0,
                    'date' => $date->toDateString(),
                ]);
                foreach ($items as $prod) {
                    $qty = rand(1, 5);
                    $subtotal = (float) $prod->price * $qty;
                    $total += $subtotal;
                    SaleDetail::create([
                        'sale_id' => $sale->id,
                        'product_id' => $prod->id,
                        'quantity' => $qty,
                        'price' => $prod->price,
                        'subtotal' => $subtotal,
                    ]);
                }
                $sale->update(['total' => $total]);
                Finance::create([
                    'type' => 'income',
                    'amount' => $total,
                    'description' => 'Pemasukan penjualan ' . $sale->invoice_number,
                    'source' => 'Penjualan',
                    'date' => $sale->date,
                ]);
            }
        }

        // --- Pengeluaran dummy ---
        $expenses = [
            ['Bahan Baku', 'Pembelian tepung & telur', 250000],
            ['Bahan Baku', 'Pembelian gula & mentega', 180000],
            ['Operasional', 'Biaya internet bulanan', 350000],
            ['Listrik', 'Tagihan listrik bulanan', 420000],
            ['Transportasi', 'Bensin delivery', 120000],
            ['Kemasan', 'Pembelian dus & sticker', 150000],
            ['Gaji', 'Gaji karyawan', 1500000],
            ['Lain-lain', 'Perbaikan oven', 275000],
        ];
        foreach ($expenses as [$source, $desc, $amount]) {
            Finance::create([
                'type' => 'expense',
                'amount' => $amount,
                'description' => $desc,
                'source' => $source,
                'date' => Carbon::today()->subDays(rand(0, 28))->toDateString(),
            ]);
        }
    }
}
