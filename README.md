# PT BOBA - PT Bikin Orang Bahagia

Website resmi **PT Bikin Orang Bahagia (PT BOBA)** — Company Profile, Marketplace Produk Fashion, dan Layanan Green Technology.

Dibangun dengan **Laravel 12 + Livewire 3 + Bootstrap 5**.

## Brand Perusahaan

| Brand | Kategori | Deskripsi |
|-------|----------|-----------|
| **tsoecha.co** | Fashion Pria | Kaos, kemeja, hoodie, jaket, celana, aksesoris pria |
| **sokyuut** | Fashion Wanita | Blouse, dress, outer, hijab, rok, celana, aksesoris wanita |
| **tos2bro** | Green Technology | Pengambilan sampah, pengelolaan sampah, pengolahan sampah organik, bahan bakar kendaraan |

## Fitur

- **Landing Page / Company Profile** — Profil PT BOBA, visi misi, brand, produk unggulan, layanan, struktur pendiri, partner, testimonial, kontak
- **Marketplace** — Jual beli produk fashion (tsoecha.co & sokyuut) dan booking layanan green technology (tos2bro)
- **Multi-role** — Admin, Buyer, Seller dengan dashboard masing-masing
- **CRUD lengkap** — Produk, layanan, struktur perusahaan, seller management
- **Cart & Checkout** — Keranjang belanja dengan simulasi pembayaran
- **Service Booking** — Booking layanan tos2bro dengan tracking status
- **Reports** — Laporan order, booking, pembayaran dengan filter tanggal

## Teknologi

- Laravel 12 · Livewire 3 · Bootstrap 5 (CDN) · Bootstrap Icons
- MySQL
- Tanpa Controller — semua logic di Livewire Component
- Authentication manual (tanpa Breeze/UI)

## Setup

```bash
# 1. Install dependencies
composer install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Konfigurasi database
# Edit .env:
# DB_CONNECTION=mysql
# DB_DATABASE=ptboba
# DB_USERNAME=root
# DB_PASSWORD=

# Buat database:
mysql -u root -e "CREATE DATABASE ptboba"

# 4. Migrate + seed
php artisan migrate:fresh --seed

# 5. Storage symlink (untuk foto/gambar)
php artisan storage:link

# 6. Jalankan
php artisan serve
```

Buka `http://localhost:8000`

## Akun Demo

| Role   | Email              | Password   |
|--------|--------------------|------------|
| Admin  | `admin@gmail.com`  | `password` |
| Buyer  | `buyer1@gmail.com` | `password` |
| Buyer  | `buyer2@gmail.com` | `password` |
| Seller | `seller1@gmail.com`| `password` |
| Seller | `seller2@gmail.com`| `password` |

## Struktur Perusahaan / Pendiri PT BOBA

| Nama | Jabatan |
|------|---------|
| Bachrul Ullum Assrori | Direktur |
| Ario Putra Bakti | Komisaris Utama |
| Ellen Sinta Budirahayu | Komisaris |

## Route Utama

```
GET  /                        — Landing Page / Company Profile
GET  /home                    — Home Page (pilih role)
GET  /login                   — Login
GET  /register                — Register Buyer
GET  /seller/register         — Register Seller

GET  /admin/dashboard         — Admin Dashboard
GET  /admin/company-structure — Manage Struktur Perusahaan
GET  /admin/sellers           — Manage Sellers
GET  /admin/products          — Manage Products
GET  /admin/services          — Manage Services
GET  /admin/reports           — View Reports

GET  /buyer/dashboard         — Buyer Dashboard
GET  /buyer/products          — Product List
GET  /buyer/products/{id}     — Product Detail
GET  /buyer/cart              — Cart
GET  /buyer/checkout          — Checkout
GET  /buyer/services          — Service List
GET  /buyer/services/{id}/booking — Service Booking
GET  /buyer/service-tracking/{id} — Service Tracking

GET  /seller/dashboard        — Seller Dashboard
GET  /seller/profile          — Seller Profile
GET  /seller/products         — Manage Products
GET  /seller/services         — Manage Services
GET  /seller/reports          — View Reports
```

## Upload Logo PT BOBA

Simpan logo sebagai:
- `public/assets/logo-boba.png` (untuk tampilan web)
- `public/assets/logo-boba.pdf` (opsional, format asli)

## Upload Foto Pendiri

Upload foto pendiri melalui halaman Admin:
1. Login sebagai admin
2. Buka menu **Struktur**
3. Klik **Edit** pada data pendiri
4. Upload foto melalui form
