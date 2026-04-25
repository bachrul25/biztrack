<div>
    @include('partials.buyer-navbar')
    <div class="container py-4">
        <div class="text-center mb-4">
            @if(file_exists(public_path('assets/logo-boba.png')))
                <img src="{{ asset('assets/logo-boba.png') }}" alt="PT BOBA" style="height:60px" class="mb-2">
            @endif
            <h4 class="fw-bold">Buyer Dashboard</h4>
            <p class="text-muted">Selamat datang, {{ Auth::user()->name }}!</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <div class="row g-4 justify-content-center">
            <div class="col-md-4 col-6">
                <a href="/buyer/products" class="text-decoration-none">
                    <div class="card border-0 shadow-sm card-hover text-center h-100">
                        <div class="card-body p-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;">
                                <i class="bi bi-bag fs-3 text-primary"></i>
                            </div>
                            <h6 class="fw-bold">View Products</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-6">
                <a href="/buyer/services" class="text-decoration-none">
                    <div class="card border-0 shadow-sm card-hover text-center h-100">
                        <div class="card-body p-4">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;">
                                <i class="bi bi-gear fs-3 text-success"></i>
                            </div>
                            <h6 class="fw-bold">View Services</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-6">
                <a href="/buyer/cart" class="text-decoration-none">
                    <div class="card border-0 shadow-sm card-hover text-center h-100">
                        <div class="card-body p-4">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;">
                                <i class="bi bi-cart fs-3 text-warning"></i>
                            </div>
                            <h6 class="fw-bold">Cart ({{ $cartCount }})</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-6">
                <div class="card border-0 shadow-sm card-hover text-center h-100">
                    <div class="card-body p-4">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;">
                            <i class="bi bi-receipt fs-3 text-info"></i>
                        </div>
                        <h6 class="fw-bold">Riwayat Order ({{ $orderCount }})</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="card border-0 shadow-sm card-hover text-center h-100">
                    <div class="card-body p-4">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;">
                            <i class="bi bi-truck fs-3 text-danger"></i>
                        </div>
                        <h6 class="fw-bold">Tracking Service ({{ $bookingCount }})</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
