<nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background:#27ae60;">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="/buyer/dashboard">
            @if(file_exists(public_path('assets/logo-boba.png')))
                <img src="{{ asset('assets/logo-boba.png') }}" alt="PT BOBA" style="height:35px" class="me-2">
            @else
                <i class="bi bi-building me-2"></i>
            @endif
            <span class="fw-bold">PT BOBA</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#buyerNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="buyerNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link {{ request()->is('buyer/dashboard') ? 'active' : '' }}" href="/buyer/dashboard"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('buyer/products*') ? 'active' : '' }}" href="/buyer/products"><i class="bi bi-bag me-1"></i> Products</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('buyer/services*') ? 'active' : '' }}" href="/buyer/services"><i class="bi bi-gear me-1"></i> Services</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('buyer/cart') ? 'active' : '' }}" href="/buyer/cart"><i class="bi bi-cart me-1"></i> Cart</a></li>
                <li class="nav-item">
                    <a class="nav-link text-warning" href="/logout"><i class="bi bi-box-arrow-right me-1"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
