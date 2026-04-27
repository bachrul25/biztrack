@php
    $user = auth()->user();
    $isAdmin = $user && $user->role === 'admin';
@endphp
<aside class="app-sidebar">
    <div class="brand">
        <i class="bi bi-shop-window"></i> BizTrack
        <small>Toko Kue Bu Nina</small>
    </div>

    <div class="nav-section">Menu Utama</div>
    <ul class="nav flex-column">
        <li>
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        @if($isAdmin)
            <li>
                <a href="{{ route('products') }}"
                   class="nav-link {{ request()->routeIs('products') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Produk
                </a>
            </li>
            <li>
                <a href="{{ route('categories') }}"
                   class="nav-link {{ request()->routeIs('categories') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Kategori
                </a>
            </li>
            <li>
                <a href="{{ route('stocks') }}"
                   class="nav-link {{ request()->routeIs('stocks') ? 'active' : '' }}">
                    <i class="bi bi-box-arrow-in-down"></i> Stok
                </a>
            </li>
            <li>
                <a href="{{ route('sales') }}"
                   class="nav-link {{ request()->routeIs('sales') ? 'active' : '' }}">
                    <i class="bi bi-cart-check"></i> Transaksi Penjualan
                </a>
            </li>
            <li>
                <a href="{{ route('finances') }}"
                   class="nav-link {{ request()->routeIs('finances') ? 'active' : '' }}">
                    <i class="bi bi-wallet2"></i> Keuangan
                </a>
            </li>
        @endif
    </ul>

    <div class="nav-section">Laporan</div>
    <ul class="nav flex-column">
        <li>
            <a href="{{ route('reports.sales') }}"
               class="nav-link {{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Laporan Penjualan
            </a>
        </li>
        <li>
            <a href="{{ route('reports.stocks') }}"
               class="nav-link {{ request()->routeIs('reports.stocks') ? 'active' : '' }}">
                <i class="bi bi-clipboard-data"></i> Laporan Stok
            </a>
        </li>
        <li>
            <a href="{{ route('reports.finance') }}"
               class="nav-link {{ request()->routeIs('reports.finance') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> Laporan Keuangan
            </a>
        </li>
        <li>
            <a href="{{ route('reports.profit-loss') }}"
               class="nav-link {{ request()->routeIs('reports.profit-loss') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i> Laporan Laba Rugi
            </a>
        </li>
    </ul>

    <div class="nav-section">Analisis</div>
    <ul class="nav flex-column">
        <li>
            <a href="{{ route('predictions.sales') }}"
               class="nav-link {{ request()->routeIs('predictions.sales') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> Prediksi Penjualan
            </a>
        </li>
    </ul>

    <div class="nav-section">Akun</div>
    <ul class="nav flex-column">
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link w-100 text-start bg-transparent border-0">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</aside>
