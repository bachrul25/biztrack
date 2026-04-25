<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);">
    <div class="container py-5">
        <div class="text-center mb-5">
            @if(file_exists(public_path('assets/logo-boba.png')))
                <img src="{{ asset('assets/logo-boba.png') }}" alt="PT BOBA" style="height:100px" class="mb-3">
            @else
                <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:100px;height:100px;">
                    <i class="bi bi-building display-4 text-white"></i>
                </div>
            @endif
            <h2 class="text-white fw-bold">Selamat Datang di PT BOBA</h2>
            <p class="text-white-50">Pilih jenis akun untuk melanjutkan</p>
        </div>

        <div class="row justify-content-center g-4">
            {{-- Buyer Card --}}
            <div class="col-md-5">
                <div class="card border-0 shadow-lg card-hover rounded-4">
                    <div class="card-body p-5 text-center">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width:100px;height:100px;">
                            <i class="bi bi-bag-heart display-4 text-success"></i>
                        </div>
                        <h3 class="fw-bold mb-2">Buyer</h3>
                        <p class="text-muted mb-4">Belanja produk fashion dan pesan layanan green technology dari PT BOBA.</p>
                        <div class="d-grid gap-2">
                            <a href="/login" class="btn btn-success btn-lg">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login Buyer
                            </a>
                            <a href="/register" class="btn btn-outline-success">
                                <i class="bi bi-person-plus me-1"></i> Register Buyer
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Seller Card --}}
            <div class="col-md-5">
                <div class="card border-0 shadow-lg card-hover rounded-4">
                    <div class="card-body p-5 text-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width:100px;height:100px;">
                            <i class="bi bi-shop display-4 text-warning"></i>
                        </div>
                        <h3 class="fw-bold mb-2">Seller</h3>
                        <p class="text-muted mb-4">Jual produk fashion atau tawarkan layanan green technology sebagai mitra PT BOBA.</p>
                        <div class="d-grid gap-2">
                            <a href="/login" class="btn btn-warning btn-lg">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login Seller
                            </a>
                            <a href="/seller/register" class="btn btn-outline-warning">
                                <i class="bi bi-person-plus me-1"></i> Register Seller
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="/" class="text-white-50"><i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda</a>
        </div>
    </div>
</div>
