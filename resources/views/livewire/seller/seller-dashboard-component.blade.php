<div>
    @include('partials.seller-navbar')
    <div class="container py-4">
        <div class="text-center mb-4">
            @if(file_exists(public_path('assets/logo-boba.png')))
                <img src="{{ asset('assets/logo-boba.png') }}" alt="PT BOBA" style="height:60px" class="mb-2">
            @endif
            <h4 class="fw-bold">Seller Dashboard</h4>
            <p class="text-muted">Selamat datang, {{ Auth::user()->name }}!</p>
        </div>

        @if($profile && $profile->status === 'pending')
            <div class="alert alert-warning"><i class="bi bi-hourglass-split me-2"></i>Status toko Anda masih <strong>pending</strong>. Menunggu approval dari admin.</div>
        @endif

        @if($profile && !$profile->is_completed)
            <div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Profil toko Anda belum lengkap. <a href="/seller/profile" class="alert-link">Lengkapi sekarang</a>.</div>
        @endif

        @if($profile && $profile->status === 'rejected')
            <div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>Pendaftaran toko Anda <strong>ditolak</strong>. Silakan hubungi admin.</div>
        @endif

        <div class="row g-3">
            <div class="col-md-4 col-6">
                <div class="card border-0 shadow-sm card-hover text-center">
                    <div class="card-body">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                            <i class="bi bi-box fs-4 text-primary"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $totalProducts }}</h3>
                        <small class="text-muted">Total Produk</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="card border-0 shadow-sm card-hover text-center">
                    <div class="card-body">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                            <i class="bi bi-gear fs-4 text-success"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $totalServices }}</h3>
                        <small class="text-muted">Total Layanan</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="card border-0 shadow-sm card-hover text-center">
                    <div class="card-body">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                            <i class="bi bi-cart-check fs-4 text-info"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $totalOrders }}</h3>
                        <small class="text-muted">Order Produk</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="card border-0 shadow-sm card-hover text-center">
                    <div class="card-body">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                            <i class="bi bi-calendar-check fs-4 text-warning"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $totalBookings }}</h3>
                        <small class="text-muted">Booking Layanan</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="card border-0 shadow-sm card-hover text-center">
                    <div class="card-body">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                            <i class="bi bi-cash-stack fs-4 text-success"></i>
                        </div>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        <small class="text-muted">Total Pendapatan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
