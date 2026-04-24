@php
    $fmtIdr = fn ($v) => 'Rp ' . number_format((float) $v, 0, ',', '.');
@endphp
<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold">Selamat datang, {{ auth()->user()->name }}</h3>
            <p class="text-muted mb-0">Ringkasan performa usaha — BizTrack Toko Kue Bu Nina</p>
        </div>
        <span class="text-muted small"><i class="bi bi-calendar3"></i> {{ now()->translatedFormat('l, d F Y') }}</span>
    </div>

    {{-- Stat cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon" style="background:#6c4ae6"><i class="bi bi-cart-check"></i></div>
                    <div>
                        <div class="text-muted small">Total Penjualan</div>
                        <div class="fs-5 fw-bold">{{ $fmtIdr($summary['total_sales']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon" style="background:#10b981"><i class="bi bi-arrow-down-circle"></i></div>
                    <div>
                        <div class="text-muted small">Pemasukan</div>
                        <div class="fs-5 fw-bold">{{ $fmtIdr($summary['total_income']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon" style="background:#ef4444"><i class="bi bi-arrow-up-circle"></i></div>
                    <div>
                        <div class="text-muted small">Pengeluaran</div>
                        <div class="fs-5 fw-bold">{{ $fmtIdr($summary['total_expense']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon" style="background:{{ $summary['net_profit'] >= 0 ? '#0ea5e9' : '#dc2626' }}">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Laba Bersih</div>
                        <div class="fs-5 fw-bold">{{ $fmtIdr($summary['net_profit']) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card content-card h-100">
                <div class="card-body">
                    <div class="text-muted small">Jumlah Produk</div>
                    <div class="fs-4 fw-bold">{{ $summary['product_count'] }}</div>
                    <span class="text-muted small"><i class="bi bi-box-seam"></i> aktif & non-aktif</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card content-card h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Stok Barang</div>
                    <div class="fs-4 fw-bold">{{ number_format($summary['total_stock'], 0, ',', '.') }}</div>
                    <span class="text-muted small"><i class="bi bi-stack"></i> semua produk</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card content-card h-100">
                <div class="card-body">
                    <div class="text-muted small">Transaksi Hari Ini</div>
                    <div class="fs-4 fw-bold">{{ $summary['today_transactions'] }}</div>
                    <span class="text-muted small"><i class="bi bi-receipt"></i> {{ now()->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card content-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-semibold">Grafik Penjualan 6 Bulan Terakhir</h6>
                        <span class="badge text-bg-light">Chart.js</span>
                    </div>
                    <canvas id="salesChart" height="110"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card content-card">
                <div class="card-body">
                    <h6 class="mb-3 fw-semibold">Pemasukan vs Pengeluaran</h6>
                    <canvas id="financeChart" height="160"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card content-card">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Produk Terlaris</h6>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-end">Qty Terjual</th>
                                    <th class="text-end">Omzet</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($topProducts as $row)
                                <tr>
                                    <td>{{ $row->product?->name ?? '—' }}</td>
                                    <td class="text-end">{{ number_format($row->total_qty, 0, ',', '.') }}</td>
                                    <td class="text-end">{{ $fmtIdr($row->total_omzet) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">Belum ada data penjualan</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card content-card">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-exclamation-triangle text-warning"></i> Produk Stok Menipis
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr><th>Produk</th><th>Kategori</th><th class="text-end">Stok</th></tr>
                            </thead>
                            <tbody>
                            @forelse ($lowStock as $p)
                                <tr>
                                    <td>{{ $p->name }}</td>
                                    <td><span class="badge text-bg-light">{{ $p->category }}</span></td>
                                    <td class="text-end">
                                        <span class="badge text-bg-{{ $p->stock_badge }}">{{ $p->stock }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">Semua stok aman</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const salesCtx = document.getElementById('salesChart');
                if (salesCtx) {
                    new Chart(salesCtx, {
                        type: 'line',
                        data: {
                            labels: @json($salesChart['labels']),
                            datasets: [{
                                label: 'Total Penjualan',
                                data: @json($salesChart['values']),
                                borderColor: '#6c4ae6',
                                backgroundColor: 'rgba(108,74,230,.12)',
                                fill: true,
                                tension: .35,
                                pointBackgroundColor: '#6c4ae6',
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true, ticks: {
                                callback: (v) => 'Rp' + new Intl.NumberFormat('id-ID').format(v)
                            } } }
                        }
                    });
                }
                const financeCtx = document.getElementById('financeChart');
                if (financeCtx) {
                    new Chart(financeCtx, {
                        type: 'bar',
                        data: {
                            labels: @json($financeChart['labels']),
                            datasets: [
                                { label: 'Pemasukan', data: @json($financeChart['income']), backgroundColor: '#10b981' },
                                { label: 'Pengeluaran', data: @json($financeChart['expense']), backgroundColor: '#ef4444' },
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: { y: { beginAtZero: true, ticks: {
                                callback: (v) => 'Rp' + new Intl.NumberFormat('id-ID').format(v)
                            } } }
                        }
                    });
                }
            })();
        </script>
    @endpush
</div>
