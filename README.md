# BizTrack — Business Tracking System

Aplikasi web manajemen usaha untuk **UMKM Toko Kue Bu Nina**. Dibangun dengan Laravel 12 + Livewire 3 + Bootstrap 5.

## Fitur

- **Dashboard** — Statistik usaha, grafik penjualan bulanan, pemasukan vs pengeluaran, produk terlaris, peringatan stok menipis.
- **Produk** — CRUD produk lengkap (nama, harga, stok, kategori, foto, status), dengan upload gambar, search, filter kategori, pagination.
- **Penjualan** — Transaksi multi-item dengan perhitungan otomatis, validasi stok (tidak bisa minus), database transaction, stok auto-berkurang, pemasukan auto-tercatat.
- **Stok** — Monitoring stok dengan status (aman / menipis / habis), update stok manual.
- **Keuangan** — Pencatatan pemasukan & pengeluaran, total laba rugi, filter tanggal & jenis.
- **Laporan** — Penjualan, Keuangan, Laba Rugi, Stok. Filter tanggal, cetak, dan export PDF.
- **Role-based access** — Admin (full akses operasional) dan Owner (view-only + laporan).

## Teknologi

- Laravel 12 · Livewire 3 · Bootstrap 5 (CDN) · Chart.js · SweetAlert2 · Barryvdh DomPDF
- MySQL (default) atau SQLite untuk development
- Arsitektur: Route → Livewire Component → Repository → Model → Database
- Controller hanya dipakai untuk auth & export PDF

## Setup

```bash
# 1. Install dependencies
composer install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Konfigurasi database (pilih salah satu)

# -- MySQL (production) --
# Edit .env:
# DB_CONNECTION=mysql
# DB_DATABASE=biztrack
# DB_USERNAME=root
# DB_PASSWORD=
# Lalu buat DB:
mysql -u root -e "CREATE DATABASE biztrack"

# -- atau SQLite (dev cepat) --
# Edit .env: DB_CONNECTION=sqlite
touch database/database.sqlite

# 4. Migrate + seed dummy
php artisan migrate:fresh --seed

# 5. Storage symlink (untuk foto produk)
php artisan storage:link

# 6. Jalankan
php artisan serve
```

Buka `http://localhost:8000` lalu login.

## Akun Demo

| Role  | Email                | Password   |
|-------|----------------------|------------|
| Admin | `admin@biztrack.com` | `password` |
| Owner | `owner@biztrack.com` | `password` |

## Struktur Project

```
app/
├── Livewire/
│   ├── DashboardComponent.php
│   ├── ProductComponent.php
│   ├── SaleComponent.php
│   ├── StockComponent.php
│   ├── FinanceComponent.php
│   └── Reports/
│       ├── SalesReportComponent.php
│       ├── FinanceReportComponent.php
│       ├── ProfitLossReportComponent.php
│       └── StockReportComponent.php
├── Models/              User · Product · Sale · SaleDetail · Finance
├── Repositories/        ProductRepository · SaleRepository · FinanceRepository · ReportRepository
└── Http/
    ├── Middleware/RoleMiddleware.php
    └── Controllers/
        ├── Auth/AuthController.php      (login/logout)
        └── ReportPdfController.php      (export PDF)

resources/views/
├── layouts/app.blade.php
├── partials/            sidebar · navbar
├── auth/                login
├── livewire/            <component>-component.blade.php (+ reports/)
└── pdf/                 sales · finance · profit-loss · stock
```

## Data Seeder

- 2 user (Admin, Owner)
- 10 produk kue (Brownies, Bolu Kukus, Nastar, Kastengel, Donat, Risoles, Lemper, Kue Lapis, Roti Sobek, Cupcake)
- ~60 transaksi penjualan dummy (30 hari terakhir)
- 8 transaksi pengeluaran dummy

## Route Utama

```
GET  /login              — halaman login
GET  /dashboard          — dashboard (admin + owner)
GET  /products           — manajemen produk (admin)
GET  /sales              — transaksi penjualan (admin)
GET  /stocks             — manajemen stok (admin)
GET  /finances           — manajemen keuangan (admin)
GET  /reports/sales      — laporan penjualan (admin + owner)
GET  /reports/finance    — laporan keuangan (admin + owner)
GET  /reports/profit-loss
GET  /reports/stocks
GET  /reports/*/pdf      — export PDF
```
