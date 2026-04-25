<div>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-boba fixed-top shadow">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                @if(file_exists(public_path('assets/logo-boba.png')))
                    <img src="{{ asset('assets/logo-boba.png') }}" alt="PT BOBA" style="height:40px" class="me-2">
                @else
                    <i class="bi bi-building me-2 fs-4"></i>
                @endif
                <span class="fw-bold">PT BOBA</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#brand">Brand</a></li>
                    <li class="nav-item"><a class="nav-link" href="#products">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#struktur">Struktur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#partner">Partner</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-success btn-sm text-white ms-2 px-3" href="/login">Login</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-outline-light btn-sm ms-2 px-3" href="/register">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section id="home" class="hero-section text-center" style="padding-top:140px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @if(file_exists(public_path('assets/logo-boba.png')))
                        <img src="{{ asset('assets/logo-boba.png') }}" alt="PT BOBA" style="height:120px" class="mb-4">
                    @else
                        <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width:120px;height:120px;">
                            <i class="bi bi-building display-3"></i>
                        </div>
                    @endif
                    <h1 class="display-4 fw-bold mb-3">PT Bikin Orang Bahagia</h1>
                    <h5 class="text-light mb-3 opacity-75">Industri Tekstil, Produk Olahan, Fashion Brand, dan Green Technology</h5>
                    <p class="lead mb-4 opacity-75">PT BOBA menghadirkan produk fashion berkualitas melalui brand <strong>tsoecha.co</strong> dan <strong>sokyuut</strong>, serta layanan ramah lingkungan melalui <strong>tos2bro</strong>.</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="/home" class="btn btn-success btn-lg px-4"><i class="bi bi-rocket-takeoff me-1"></i> Mulai Sekarang</a>
                        <a href="#products" class="btn btn-outline-light btn-lg px-4"><i class="bi bi-bag me-1"></i> Lihat Produk</a>
                        <a href="#services" class="btn btn-outline-light btn-lg px-4"><i class="bi bi-gear me-1"></i> Lihat Layanan</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- About Company --}}
    <section id="about" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Tentang PT BOBA</h2>
                <div class="mx-auto" style="width:60px;height:4px;background:#27ae60;border-radius:2px;"></div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <p class="lead">PT Bikin Orang Bahagia (PT BOBA) adalah perusahaan yang bergerak di bidang industri tekstil, produk olahan, fashion brand, dan layanan green technology.</p>
                    <p>PT BOBA menaungi brand fashion <strong>tsoecha.co</strong> untuk pria dan <strong>sokyuut</strong> untuk wanita, serta brand green technology <strong>tos2bro</strong> yang bergerak di bidang pengelolaan sampah dan energi terbarukan.</p>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="fw-bold text-success"><i class="bi bi-eye me-2"></i>Visi</h5>
                            <p class="mb-0">Menjadi perusahaan terdepan yang menghadirkan kebahagiaan melalui produk fashion berkualitas dan layanan green technology yang berkelanjutan.</p>
                        </div>
                    </div>
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="fw-bold text-success"><i class="bi bi-bullseye me-2"></i>Misi</h5>
                            <ul class="mb-0">
                                <li>Mengembangkan brand fashion lokal berkualitas tinggi</li>
                                <li>Menyediakan layanan pengelolaan sampah modern</li>
                                <li>Mendukung ekonomi kreatif dan green technology</li>
                                <li>Memberdayakan mitra dan seller lokal</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold text-success"><i class="bi bi-heart me-2"></i>Nilai Perusahaan</h5>
                            <p class="mb-0">Inovasi, Keberlanjutan, Kualitas, Kebahagiaan Pelanggan, dan Tanggung Jawab Sosial.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Brand Section --}}
    <section id="brand" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Brand Kami</h2>
                <div class="mx-auto" style="width:60px;height:4px;background:#27ae60;border-radius:2px;"></div>
                <p class="text-muted mt-2">Tiga brand unggulan PT BOBA</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow card-hover h-100 text-center">
                        <div class="card-body p-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                                <i class="bi bi-person-standing fs-1 text-primary"></i>
                            </div>
                            <h4 class="fw-bold">tsoecha.co</h4>
                            <span class="badge bg-primary mb-2">Brand Fashion Pria</span>
                            <p class="text-muted">Menyediakan produk fashion pria berkualitas: kaos, kemeja, hoodie, jaket, celana, dan aksesoris pria.</p>
                            <a href="#products" wire:click="$set('brandFilter', 'tsoecha.co')" class="btn btn-outline-primary">Lihat Produk Pria</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow card-hover h-100 text-center">
                        <div class="card-body p-4">
                            <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                                <i class="bi bi-person-standing-dress fs-1 text-danger"></i>
                            </div>
                            <h4 class="fw-bold">sokyuut</h4>
                            <span class="badge bg-danger mb-2">Brand Fashion Wanita</span>
                            <p class="text-muted">Menyediakan produk fashion wanita: blouse, dress, outer, hijab, rok, celana, dan aksesoris wanita.</p>
                            <a href="#products" wire:click="$set('brandFilter', 'sokyuut')" class="btn btn-outline-danger">Lihat Produk Wanita</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow card-hover h-100 text-center">
                        <div class="card-body p-4">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                                <i class="bi bi-recycle fs-1 text-success"></i>
                            </div>
                            <h4 class="fw-bold">tos2bro</h4>
                            <span class="badge bg-success mb-2">Green Technology</span>
                            <p class="text-muted">Menyediakan jasa pengambilan sampah, pengelolaan sampah, pengolahan sampah organik, dan pembuatan bahan bakar kendaraan.</p>
                            <a href="#services" class="btn btn-outline-success">Pesan Layanan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Struktur Perusahaan --}}
    <section id="struktur" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Pendiri PT BOBA</h2>
                <div class="mx-auto" style="width:60px;height:4px;background:#27ae60;border-radius:2px;"></div>
                <p class="text-muted mt-2">Struktur Perusahaan</p>
            </div>
            <div class="row g-4 justify-content-center">
                @forelse($structures as $person)
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow card-hover text-center h-100">
                        <div class="card-body p-4">
                            @if($person->photo)
                                <img src="{{ asset('storage/' . $person->photo) }}" class="rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover;" alt="{{ $person->name }}">
                            @else
                                <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:120px;height:120px;">
                                    <i class="bi bi-person fs-1 text-secondary"></i>
                                </div>
                            @endif
                            <h5 class="fw-bold mb-1">{{ $person->name }}</h5>
                            <span class="badge bg-success mb-2">{{ $person->position }}</span>
                            <p class="text-muted small">{{ $person->description }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted">
                    <p>Data struktur perusahaan belum tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Products Section --}}
    <section id="products" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Produk Unggulan</h2>
                <div class="mx-auto" style="width:60px;height:4px;background:#27ae60;border-radius:2px;"></div>
                <p class="text-muted mt-2">Produk fashion dari brand tsoecha.co dan sokyuut</p>
            </div>

            <div class="text-center mb-4">
                <button wire:click="$set('brandFilter', '')" class="btn btn-sm {{ $brandFilter == '' ? 'btn-success' : 'btn-outline-success' }} me-1">Semua</button>
                <button wire:click="$set('brandFilter', 'tsoecha.co')" class="btn btn-sm {{ $brandFilter == 'tsoecha.co' ? 'btn-primary' : 'btn-outline-primary' }} me-1">tsoecha.co</button>
                <button wire:click="$set('brandFilter', 'sokyuut')" class="btn btn-sm {{ $brandFilter == 'sokyuut' ? 'btn-danger' : 'btn-outline-danger' }}">sokyuut</button>
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow card-hover h-100">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="height:220px;object-fit:cover;" alt="{{ $product->name }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height:220px;">
                                <i class="bi bi-image text-muted" style="font-size:3rem;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge {{ $product->brand == 'tsoecha.co' ? 'bg-primary' : 'bg-danger' }}">{{ $product->brand }}</span>
                                <span class="badge bg-secondary">{{ $product->gender_category }}</span>
                            </div>
                            <h6 class="fw-bold">{{ $product->name }}</h6>
                            <p class="text-muted small mb-2">{{ $product->category }}</p>
                            <h5 class="text-success fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</h5>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3">
                            <a href="/login" class="btn btn-success btn-sm w-100">
                                <i class="bi bi-eye me-1"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted">
                    <p>Belum ada produk tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Services Section --}}
    <section id="services" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Layanan tos2bro</h2>
                <div class="mx-auto" style="width:60px;height:4px;background:#27ae60;border-radius:2px;"></div>
                <p class="text-muted mt-2">Green Technology Services</p>
            </div>
            <div class="row g-4">
                @forelse($services as $service)
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow card-hover h-100">
                        @if($service->image)
                            <img src="{{ asset('storage/' . $service->image) }}" class="card-img-top" style="height:180px;object-fit:cover;" alt="{{ $service->name }}">
                        @else
                            <div class="bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="height:180px;">
                                <i class="bi bi-recycle text-success" style="font-size:3rem;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <span class="badge bg-success mb-2">{{ str_replace('_', ' ', ucwords($service->service_type, '_')) }}</span>
                            <h6 class="fw-bold">{{ $service->name }}</h6>
                            <p class="text-muted small">{{ Str::limit($service->description, 80) }}</p>
                            <h6 class="text-success fw-bold">Rp {{ number_format($service->price, 0, ',', '.') }}</h6>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3">
                            <a href="/login" class="btn btn-success btn-sm w-100">
                                <i class="bi bi-calendar-check me-1"></i> Booking Service
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted">
                    <p>Belum ada layanan tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Mengapa Memilih Kami?</h2>
                <div class="mx-auto" style="width:60px;height:4px;background:#27ae60;border-radius:2px;"></div>
            </div>
            <div class="row g-4">
                @php
                $reasons = [
                    ['icon' => 'bi-award', 'title' => 'Produk Fashion Berkualitas', 'desc' => 'Produk fashion dari brand tsoecha.co dan sokyuut dengan kualitas terbaik.'],
                    ['icon' => 'bi-shop', 'title' => 'Brand Lokal Terpercaya', 'desc' => 'Brand lokal yang sudah dipercaya oleh banyak pelanggan di Indonesia.'],
                    ['icon' => 'bi-tree', 'title' => 'Gaya Hidup Ramah Lingkungan', 'desc' => 'Mendukung gaya hidup ramah lingkungan melalui layanan tos2bro.'],
                    ['icon' => 'bi-recycle', 'title' => 'Pengelolaan Sampah Modern', 'desc' => 'Layanan pengambilan, pengelolaan, dan pengolahan sampah dengan teknologi modern.'],
                    ['icon' => 'bi-shield-check', 'title' => 'Transaksi Mudah & Aman', 'desc' => 'Sistem transaksi yang mudah, aman, dan terpercaya untuk semua produk dan layanan.'],
                    ['icon' => 'bi-lightbulb', 'title' => 'Ekonomi Kreatif & Green Tech', 'desc' => 'Mendukung perkembangan ekonomi kreatif dan green technology di Indonesia.'],
                ];
                @endphp
                @foreach($reasons as $reason)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm card-hover h-100">
                        <div class="card-body text-center p-4">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;">
                                <i class="bi {{ $reason['icon'] }} fs-3 text-success"></i>
                            </div>
                            <h6 class="fw-bold">{{ $reason['title'] }}</h6>
                            <p class="text-muted small mb-0">{{ $reason['desc'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Partner / Seller Section --}}
    <section id="partner" class="py-5" style="background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);">
        <div class="container text-center text-white">
            <h2 class="fw-bold mb-3">Bergabung Menjadi Mitra PT BOBA</h2>
            <p class="lead mb-4 opacity-75">Jadilah bagian dari ekosistem PT BOBA dan kembangkan bisnis Anda bersama kami.</p>
            <a href="/seller/register" class="btn btn-light btn-lg px-5 fw-semibold">
                <i class="bi bi-shop me-1"></i> Daftar Sebagai Seller
            </a>
        </div>
    </section>

    {{-- Testimonial Section --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Testimoni Pelanggan</h2>
                <div class="mx-auto" style="width:60px;height:4px;background:#27ae60;border-radius:2px;"></div>
            </div>
            <div class="row g-4">
                @php
                $testimonials = [
                    ['name' => 'Ahmad Rizki', 'role' => 'Pelanggan tsoecha.co', 'text' => 'Produk dari tsoecha.co kualitasnya sangat baik. Bahannya adem dan nyaman dipakai sehari-hari. Sangat recommended!', 'icon' => 'bi-person-circle'],
                    ['name' => 'Sari Dewi', 'role' => 'Pelanggan sokyuut', 'text' => 'Koleksi sokyuut selalu up to date dan desainnya cantik. Cocok banget untuk gaya casual maupun formal!', 'icon' => 'bi-person-circle'],
                    ['name' => 'Budi Santoso', 'role' => 'Pengguna tos2bro', 'text' => 'Layanan pengambilan sampah dari tos2bro sangat membantu. Tepat waktu dan ramah lingkungan. Luar biasa!', 'icon' => 'bi-person-circle'],
                ];
                @endphp
                @foreach($testimonials as $testi)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                            <p class="text-muted fst-italic">"{{ $testi['text'] }}"</p>
                            <div class="d-flex align-items-center">
                                <i class="bi {{ $testi['icon'] }} fs-2 text-secondary me-2"></i>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $testi['name'] }}</h6>
                                    <small class="text-muted">{{ $testi['role'] }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Contact Section --}}
    <section id="contact" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Hubungi Kami</h2>
                <div class="mx-auto" style="width:60px;height:4px;background:#27ae60;border-radius:2px;"></div>
            </div>
            <div class="row g-4">
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Informasi Kontak</h5>
                            <div class="mb-3">
                                <i class="bi bi-geo-alt text-success me-2"></i>
                                <strong>Alamat:</strong><br>
                                <span class="text-muted ms-4">Jl. Industri Kreatif No. 88, Jakarta Selatan, Indonesia</span>
                            </div>
                            <div class="mb-3">
                                <i class="bi bi-envelope text-success me-2"></i>
                                <strong>Email:</strong><br>
                                <span class="text-muted ms-4">info@ptboba.co.id</span>
                            </div>
                            <div class="mb-3">
                                <i class="bi bi-whatsapp text-success me-2"></i>
                                <strong>WhatsApp:</strong><br>
                                <span class="text-muted ms-4">+62 812-3456-7890</span>
                            </div>
                            <div class="mb-3">
                                <i class="bi bi-clock text-success me-2"></i>
                                <strong>Jam Operasional:</strong><br>
                                <span class="text-muted ms-4">Senin - Jumat: 08:00 - 17:00 WIB</span><br>
                                <span class="text-muted ms-4">Sabtu: 09:00 - 14:00 WIB</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Kirim Pesan</h5>
                            <form>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama</label>
                                        <input type="text" class="form-control" placeholder="Nama lengkap">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Subjek</label>
                                    <input type="text" class="form-control" placeholder="Subjek pesan">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pesan</label>
                                    <textarea class="form-control" rows="4" placeholder="Tulis pesan Anda"></textarea>
                                </div>
                                <button type="button" class="btn btn-success px-4">
                                    <i class="bi bi-send me-1"></i> Kirim Pesan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    @if(file_exists(public_path('assets/logo-boba.png')))
                        <img src="{{ asset('assets/logo-boba.png') }}" alt="PT BOBA" style="height:50px" class="mb-3">
                    @else
                        <h4 class="text-white fw-bold"><i class="bi bi-building me-2"></i>PT BOBA</h4>
                    @endif
                    <p class="small">PT Bikin Orang Bahagia - Perusahaan yang bergerak di bidang industri tekstil, fashion brand, dan layanan green technology.</p>
                </div>
                <div class="col-md-2">
                    <h6 class="text-white fw-bold mb-3">Navigasi</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#home">Home</a></li>
                        <li class="mb-2"><a href="#about">About</a></li>
                        <li class="mb-2"><a href="#brand">Brand</a></li>
                        <li class="mb-2"><a href="#products">Products</a></li>
                        <li class="mb-2"><a href="#services">Services</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-white fw-bold mb-3">Struktur Perusahaan</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><strong>Direktur:</strong> Bachrul Ullum Assrori</li>
                        <li class="mb-2"><strong>Komisaris Utama:</strong> Ario Putra Bakti</li>
                        <li class="mb-2"><strong>Komisaris:</strong> Ellen Sinta Budirahayu</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-white fw-bold mb-3">Sosial Media</h6>
                    <div class="d-flex gap-3 mb-3">
                        <a href="#" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-tiktok"></i></a>
                    </div>
                    <p class="small"><i class="bi bi-envelope me-1"></i> info@ptboba.co.id</p>
                    <p class="small"><i class="bi bi-whatsapp me-1"></i> +62 812-3456-7890</p>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="text-center small">
                <p class="mb-0">&copy; {{ date('Y') }} PT Bikin Orang Bahagia (PT BOBA). All Rights Reserved.</p>
            </div>
        </div>
    </footer>
</div>
