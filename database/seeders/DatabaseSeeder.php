<?php

namespace Database\Seeders;

use App\Models\CompanyStructure;
use App\Models\Product;
use App\Models\SellerProfile;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081200000000',
            'address' => 'Jakarta, Indonesia',
        ]);

        // Buyers
        $buyer1 = User::create([
            'name' => 'Ahmad Rizki',
            'email' => 'buyer1@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'phone' => '081200000001',
            'address' => 'Jl. Merdeka No. 10, Jakarta',
        ]);

        $buyer2 = User::create([
            'name' => 'Sari Dewi',
            'email' => 'buyer2@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'phone' => '081200000002',
            'address' => 'Jl. Sudirman No. 25, Bandung',
        ]);

        // Sellers
        $seller1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'seller1@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'phone' => '081200000003',
            'address' => 'Jl. Industri No. 5, Surabaya',
        ]);

        SellerProfile::create([
            'user_id' => $seller1->id,
            'shop_name' => 'Toko Fashion Budi',
            'shop_description' => 'Menjual berbagai produk fashion pria dan wanita berkualitas dari brand tsoecha.co dan sokyuut.',
            'shop_address' => 'Jl. Industri No. 5, Surabaya',
            'status' => 'approved',
            'is_completed' => true,
        ]);

        $seller2 = User::create([
            'name' => 'Rina Wati',
            'email' => 'seller2@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'phone' => '081200000004',
            'address' => 'Jl. Lingkungan No. 12, Yogyakarta',
        ]);

        SellerProfile::create([
            'user_id' => $seller2->id,
            'shop_name' => 'Green Service Rina',
            'shop_description' => 'Menyediakan layanan green technology dan fashion dari tos2bro, tsoecha.co, dan sokyuut.',
            'shop_address' => 'Jl. Lingkungan No. 12, Yogyakarta',
            'status' => 'approved',
            'is_completed' => true,
        ]);

        // Company Structures
        CompanyStructure::create([
            'name' => 'Bachrul Ullum Assrori',
            'position' => 'Direktur',
            'description' => 'Bertanggung jawab atas pengambilan keputusan utama, arah bisnis perusahaan, pengembangan brand, kerja sama, serta pengawasan operasional PT BOBA.',
            'sort_order' => 1,
            'status' => 'active',
        ]);

        CompanyStructure::create([
            'name' => 'Ario Putra Bakti',
            'position' => 'Komisaris Utama',
            'description' => 'Bertanggung jawab dalam pengawasan utama terhadap kebijakan perusahaan, memberikan arahan strategis, serta memastikan perusahaan berjalan sesuai tujuan perusahaan.',
            'sort_order' => 2,
            'status' => 'active',
        ]);

        CompanyStructure::create([
            'name' => 'Ellen Sinta Budirahayu',
            'position' => 'Komisaris',
            'description' => 'Bertanggung jawab membantu pengawasan perusahaan, memberikan masukan terhadap pengembangan bisnis, dan mendukung keberlanjutan perusahaan.',
            'sort_order' => 3,
            'status' => 'active',
        ]);

        // Products - tsoecha.co (brand pria)
        Product::create([
            'seller_id' => $seller1->id,
            'brand' => 'tsoecha.co',
            'gender_category' => 'pria',
            'name' => 'Kaos Polos Premium',
            'description' => 'Kaos polos premium dari tsoecha.co dengan bahan cotton combed 30s yang nyaman dan adem.',
            'price' => 89000,
            'stock' => 50,
            'category' => 'Kaos',
            'status' => 'active',
        ]);

        Product::create([
            'seller_id' => $seller1->id,
            'brand' => 'tsoecha.co',
            'gender_category' => 'pria',
            'name' => 'Kemeja Flannel Classic',
            'description' => 'Kemeja flannel classic dari tsoecha.co dengan desain timeless dan bahan berkualitas.',
            'price' => 175000,
            'stock' => 30,
            'category' => 'Kemeja',
            'status' => 'active',
        ]);

        Product::create([
            'seller_id' => $seller1->id,
            'brand' => 'tsoecha.co',
            'gender_category' => 'pria',
            'name' => 'Hoodie Urban Style',
            'description' => 'Hoodie urban style dari tsoecha.co dengan bahan fleece tebal dan hangat.',
            'price' => 250000,
            'stock' => 25,
            'category' => 'Hoodie',
            'status' => 'active',
        ]);

        // Products - sokyuut (brand wanita)
        Product::create([
            'seller_id' => $seller2->id,
            'brand' => 'sokyuut',
            'gender_category' => 'wanita',
            'name' => 'Blouse Elegant Rose',
            'description' => 'Blouse elegant dari sokyuut dengan motif bunga yang cantik dan bahan sutra.',
            'price' => 145000,
            'stock' => 40,
            'category' => 'Blouse',
            'status' => 'active',
        ]);

        Product::create([
            'seller_id' => $seller2->id,
            'brand' => 'sokyuut',
            'gender_category' => 'wanita',
            'name' => 'Dress Casual Chic',
            'description' => 'Dress casual chic dari sokyuut yang cocok untuk acara santai maupun semi formal.',
            'price' => 225000,
            'stock' => 20,
            'category' => 'Dress',
            'status' => 'active',
        ]);

        Product::create([
            'seller_id' => $seller2->id,
            'brand' => 'sokyuut',
            'gender_category' => 'wanita',
            'name' => 'Hijab Pashmina Premium',
            'description' => 'Hijab pashmina premium dari sokyuut dengan bahan voal yang lembut dan tidak mudah kusut.',
            'price' => 75000,
            'stock' => 100,
            'category' => 'Hijab',
            'status' => 'active',
        ]);

        // Services - tos2bro
        Service::create([
            'seller_id' => $seller2->id,
            'brand' => 'tos2bro',
            'service_type' => 'pengambilan_sampah',
            'name' => 'Jasa Pengambilan Sampah Rumah Tangga',
            'description' => 'Layanan pengambilan sampah rumah tangga secara berkala dengan armada yang bersih dan tepat waktu.',
            'price' => 150000,
            'category' => 'Pengambilan',
            'status' => 'active',
        ]);

        Service::create([
            'seller_id' => $seller2->id,
            'brand' => 'tos2bro',
            'service_type' => 'pengelolaan_sampah',
            'name' => 'Jasa Pengelolaan Sampah Industri',
            'description' => 'Layanan pengelolaan sampah industri dengan sistem pemilahan dan pengolahan modern.',
            'price' => 500000,
            'category' => 'Pengelolaan',
            'status' => 'active',
        ]);

        Service::create([
            'seller_id' => $seller2->id,
            'brand' => 'tos2bro',
            'service_type' => 'pengolahan_sampah_organik',
            'name' => 'Pengolahan Sampah Organik Menjadi Kompos',
            'description' => 'Layanan pengolahan sampah organik menjadi kompos berkualitas tinggi untuk pertanian dan perkebunan.',
            'price' => 350000,
            'category' => 'Pengolahan',
            'status' => 'active',
        ]);

        Service::create([
            'seller_id' => $seller2->id,
            'brand' => 'tos2bro',
            'service_type' => 'bahan_bakar_kendaraan',
            'name' => 'Konversi Sampah Organik ke Bahan Bakar',
            'description' => 'Layanan pembuatan bahan bakar kendaraan dari sampah organik menggunakan teknologi biogas modern.',
            'price' => 750000,
            'category' => 'Energi',
            'status' => 'active',
        ]);
    }
}
