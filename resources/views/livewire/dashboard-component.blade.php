@php use App\Helpers\FormatHelper as F; @endphp
<div>
    <div class="page-header d-flex justify-content-between align-items-end flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Dashboard</h1>
            <p class="text-muted mb-0">Ringkasan perkembangan usaha Toko Kue Bu Nina</p>
        </div>
        <div class="text-muted small">{{ F::tanggal(now()) }}</div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon" style="background:#b86b47"><i class="bi bi-box-seam"></i></div>
                    <div>
                        <div class="text-muted small">Total Produk</div>
                        <div class="fs-4 fw-bold">{{ F::number($totalProducts) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon" style="background:#f4a7b9"><i class="bi bi-layers"></i></div>
                    <div>
                        <div class="text-muted small">Total Stok</div>
                        <div class="fs-4 fw-bold">{{ F::number($totalStock) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon" style="background:#6c8d4e"><i class="bi bi-cart-check"></i></div>
                    <div>
                        <div class="text-muted small">Total Transaksi</div>
                        <div class="fs-4 fw-bold">{{ F::number($totalTransactions) }}</div>
                        <div class="small text-muted">Hari ini: {{ F::number($todayTxnCount) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon" style="background:#c87a54"><i class="bi bi-cash-coin"></i></div>
                    <div>
                        <div class="text-muted small">Total Pendapatan</div>
                        <div class="fs-5 fw-bold">{{ F::rupiah($totalRevenue) }}</div>
                        <div class="small text-muted">Hari ini: {{ F::rupiah($todaySales) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card p-3">
                <div class="text-muted small">Total Pengeluaran</div>
                <div class="fs-5 fw-bold text-danger">{{ F::rupiah($totalExpense) }}</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card p-3">
                <div class="text-muted small">Total Modal Terjual</div>
                <div class="fs-5 fw-bold">{{ F::rupiah($totalCost) }}</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card p-3">
                <div class="text-muted small">Laba Kotor</div>
                <div class="fs-5 fw-bold text-success">{{ F::rupiah($grossProfit) }}</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card p-3">
                <div class="text-muted small">Laba Bersih</div>
                <div class="fs-5 fw-bold {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">{{ F::rupiah($netProfit) }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card content-card">
                <div class="card-body">
                    <h6 class="card-title fw-semibold">Grafik Penjualan 12 Bulan</h6>
                    <canvas id="salesMonthlyChart" height="110"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card content-card">
                <div class="card-body">
                    <h6 class="card-title fw-semibold">Penjualan 30 Hari Terakhir</h6>
                    <canvas id="salesDailyChart" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="card content-card">
                <div class="card-body">
                    <h6 class="card-title fw-semibold">Pemasukan vs Pengeluaran</h6>
                    <canvas id="incomeExpenseChart" height="180"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card content-card">
                <div class="card-body">
                    <h6 class="card-title fw-semibold">Grafik Laba Rugi</h6>
                    <canvas id="profitLossChart" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-8">
            <div class="card content-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title fw-semibold mb-0">Prediksi Penjualan 3 Bulan ke Depan</h6>
                        <a href="{{ route('predictions.sales') }}" class="btn btn-sm btn-outline-primary">Lihat detail prediksi</a>
                    </div>
                    <p class="text-muted small mb-2">Metode: Linear Trend Projection (data bulanan)</p>
                    <canvas id="predictionChart" height="140"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card content-card h-100">
                <div class="card-body">
                    <h6 class="card-title fw-semibold">Rekomendasi Stok</h6>
                    @if($stockRecommendation)
                        <div class="small text-muted">Prediksi bulan depan</div>
                        <div class="fs-4 fw-bold">{{ F::number($stockRecommendation['forecast']) }} unit</div>
                        <div class="small text-muted">Safety stock {{ $stockRecommendation['safety_percentage'] }}%: {{ F::number($stockRecommendation['safety_stock']) }} unit</div>
                        <hr>
                        <div class="small text-muted">Rekomendasi minimal stok</div>
                        <div class="fs-3 fw-bold text-success">{{ F::number($stockRecommendation['recommended_stock']) }} unit</div>
                    @else
                        <p class="text-muted small">Butuh minimal 2 bulan data penjualan untuk menampilkan rekomendasi.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-7">
            <div class="card content-card">
                <div class="card-body">
                    <h6 class="card-title fw-semibold">Produk Terlaris (30 Hari)</h6>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr><th>#</th><th>Produk</th><th class="text-end">Qty Terjual</th><th class="text-end">Pendapatan</th></tr>
                            </thead>
                            <tbody>
                            @forelse($topProducts as $i => $row)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $row['name'] }}</td>
                                    <td class="text-end">{{ F::number($row['qty']) }}</td>
                                    <td class="text-end">{{ F::rupiah($row['revenue']) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">Belum ada transaksi.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card content-card h-100">
                <div class="card-body">
                    <h6 class="card-title fw-semibold">Stok Menipis</h6>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr><th>Produk</th><th class="text-end">Stok</th><th class="text-end">Min</th></tr></thead>
                            <tbody>
                            @forelse($lowStock as $p)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $p->name }}</div>
                                        <div class="small text-muted">{{ $p->category?->name ?? '—' }}</div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge text-bg-{{ $p->stock_badge }}">{{ $p->stock }} {{ $p->unit }}</span>
                                    </td>
                                    <td class="text-end text-muted">{{ $p->minimum_stock }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">Stok aman semua.</td></tr>
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
            const monthly = @json($monthlySeries);
            const daily = @json($dailySeries);
            const ie = @json($incomeExpenseSeries);
            const prediction = @json($predictionChart);

            function draw(ctxId, type, labels, datasets, opts = {}) {
                const el = document.getElementById(ctxId);
                if (!el) return;
                return new Chart(el, {
                    type,
                    data: { labels, datasets },
                    options: Object.assign({
                        responsive: true,
                        plugins: { legend: { labels: { boxWidth: 12 } } },
                        scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) } } }
                    }, opts),
                });
            }

            draw('salesMonthlyChart', 'bar',
                monthly.map(r => r.period),
                [{ label: 'Penjualan', data: monthly.map(r => r.total), backgroundColor: '#b86b47' }],
            );

            draw('salesDailyChart', 'line',
                daily.map(r => r.date.slice(5)),
                [{ label: 'Penjualan Harian', data: daily.map(r => r.total), borderColor: '#b86b47', backgroundColor: 'rgba(184,107,71,0.15)', fill: true, tension: .3 }],
            );

            const ieLabels = Object.keys(ie);
            draw('incomeExpenseChart', 'bar', ieLabels, [
                { label: 'Pemasukan', data: ieLabels.map(k => ie[k].income), backgroundColor: '#6c8d4e' },
                { label: 'Pengeluaran', data: ieLabels.map(k => ie[k].expense), backgroundColor: '#c85a5a' },
            ]);

            draw('profitLossChart', 'line', ieLabels, [
                { label: 'Laba Bersih', data: ieLabels.map(k => ie[k].income - ie[k].expense), borderColor: '#8f4e30', backgroundColor: 'rgba(143,78,48,0.15)', fill: true, tension: .3 },
            ]);

            if (prediction) {
                draw('predictionChart', 'line', prediction.labels, [
                    { label: 'Aktual', data: prediction.actual, borderColor: '#8f4e30', backgroundColor: 'rgba(143,78,48,0.15)', fill: false, tension: .3 },
                    { label: 'Prediksi', data: prediction.predicted, borderColor: '#f4a7b9', backgroundColor: 'rgba(244,167,185,0.2)', borderDash: [6, 4], fill: false, tension: .3 },
                ], { scales: { y: { beginAtZero: true } } });
            }
        })();
    </script>
    @endpush
</div>
