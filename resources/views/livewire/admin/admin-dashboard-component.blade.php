<div>
    @include('partials.admin-navbar')
    <div class="container-fluid py-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h4>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                            <i class="bi bi-shop fs-4 text-warning"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $totalSellers }}</h3>
                        <small class="text-muted">Total Seller</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                            <i class="bi bi-people fs-4 text-info"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $totalBuyers }}</h3>
                        <small class="text-muted">Total Buyer</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                            <i class="bi bi-box fs-4 text-primary"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $totalProducts }}</h3>
                        <small class="text-muted">Total Produk</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                            <i class="bi bi-gear fs-4 text-success"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $totalServices }}</h3>
                        <small class="text-muted">Total Layanan</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                            <i class="bi bi-diagram-3 fs-4 text-secondary"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $totalStructures }}</h3>
                        <small class="text-muted">Struktur Aktif</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                            <i class="bi bi-cart-check fs-4 text-danger"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $totalOrders }}</h3>
                        <small class="text-muted">Total Order</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                            <i class="bi bi-calendar-check fs-4 text-info"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $totalBookings }}</h3>
                        <small class="text-muted">Total Booking</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
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
