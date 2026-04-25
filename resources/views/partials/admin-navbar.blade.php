<nav class="navbar navbar-expand-lg navbar-dark bg-boba shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="/admin/dashboard">
            @if(file_exists(public_path('assets/logo-boba.png')))
                <img src="{{ asset('assets/logo-boba.png') }}" alt="PT BOBA" style="height:35px" class="me-2">
            @else
                <i class="bi bi-building me-2"></i>
            @endif
            <span class="fw-bold">PT BOBA Admin</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="/admin/dashboard"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('admin/company-structure') ? 'active' : '' }}" href="/admin/company-structure"><i class="bi bi-people me-1"></i> Struktur</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('admin/sellers') ? 'active' : '' }}" href="/admin/sellers"><i class="bi bi-shop me-1"></i> Sellers</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('admin/products') ? 'active' : '' }}" href="/admin/products"><i class="bi bi-box me-1"></i> Products</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('admin/services') ? 'active' : '' }}" href="/admin/services"><i class="bi bi-gear me-1"></i> Services</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('admin/reports') ? 'active' : '' }}" href="/admin/reports"><i class="bi bi-bar-chart me-1"></i> Reports</a></li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="/logout"><i class="bi bi-box-arrow-right me-1"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
