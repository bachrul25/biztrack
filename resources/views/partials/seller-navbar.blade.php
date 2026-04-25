<nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background:#e67e22;">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="/seller/dashboard">
            @if(file_exists(public_path('assets/logo-boba.png')))
                <img src="{{ asset('assets/logo-boba.png') }}" alt="PT BOBA" style="height:35px" class="me-2">
            @else
                <i class="bi bi-building me-2"></i>
            @endif
            <span class="fw-bold">PT BOBA Seller</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sellerNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="sellerNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link {{ request()->is('seller/dashboard') ? 'active' : '' }}" href="/seller/dashboard"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('seller/profile') ? 'active' : '' }}" href="/seller/profile"><i class="bi bi-person me-1"></i> Profile</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('seller/products') ? 'active' : '' }}" href="/seller/products"><i class="bi bi-box me-1"></i> Products</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('seller/services') ? 'active' : '' }}" href="/seller/services"><i class="bi bi-gear me-1"></i> Services</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('seller/reports') ? 'active' : '' }}" href="/seller/reports"><i class="bi bi-bar-chart me-1"></i> Reports</a></li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/logout"><i class="bi bi-box-arrow-right me-1"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
