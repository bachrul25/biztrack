@php $user = auth()->user(); @endphp
<div class="app-navbar d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
        <button id="sidebarToggle" class="btn btn-light d-lg-none" type="button">
            <i class="bi bi-list"></i>
        </button>
        <h5 class="mb-0 text-muted fw-semibold">Toko Kue Bu Nina</h5>
    </div>

    <div class="d-flex align-items-center gap-3">
        <div class="text-end d-none d-sm-block">
            <div class="fw-semibold" style="line-height: 1">{{ $user?->name }}</div>
            <span class="badge text-bg-{{ $user?->role === 'admin' ? 'primary' : 'success' }} text-uppercase">
                {{ $user?->role }}
            </span>
        </div>
        <div class="dropdown">
            <button class="btn btn-light rounded-circle" data-bs-toggle="dropdown" type="button">
                <i class="bi bi-person-circle fs-5"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li><span class="dropdown-item-text fw-semibold">{{ $user?->name }}</span></li>
                <li><span class="dropdown-item-text text-muted small">{{ $user?->email }}</span></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
