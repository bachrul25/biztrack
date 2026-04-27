@php use App\Helpers\FormatHelper as F; @endphp
<div>
    <div class="page-header mb-4">
        <h1 class="h3 fw-bold mb-1">Prediksi Penjualan — Time Series</h1>
        <p class="text-muted mb-0">Implementasi 6 metode Time Series untuk memprediksi penjualan Toko Kue Bu Nina</p>
    </div>

    <div class="card content-card mb-3">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Konfigurasi Prediksi</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Produk</label>
                    <select wire:model="productId" class="form-select">
                        <option value="">Semua Produk</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Periode Data</label>
                    <select wire:model="periodType" class="form-select">
                        <option value="daily">Harian</option>
                        <option value="weekly">Mingguan</option>
                        <option value="monthly">Bulanan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Metode Prediksi</label>
                    <select wire:model.live="method" class="form-select">
                        @foreach($methods as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jumlah Periode ke Depan</label>
                    <input type="number" min="1" max="24" wire:model="horizon" class="form-control">
                </div>

                @if($method === 'moving_average')
                <div class="col-md-3">
                    <label class="form-label">Window (n periode)</label>
                    <input type="number" min="2" max="12" wire:model="window" class="form-control">
                </div>
                @endif

                @if($method === 'weighted_moving_average')
                <div class="col-md-4">
                    <label class="form-label">Bobot (dipisahkan koma, terakhir = terbaru)</label>
                    <input type="text" wire:model="weights" class="form-control" placeholder="1,2,3">
                </div>
                @endif

                @if(in_array($method, ['single_exponential_smoothing', 'double_exponential_smoothing']))
                <div class="col-md-3">
                    <label class="form-label">Alpha (α)</label>
                    <input type="number" step="0.05" min="0.01" max="1" wire:model="alpha" class="form-control">
                </div>
                @endif

                @if($method === 'double_exponential_smoothing')
                <div class="col-md-3">
                    <label class="form-label">Beta (β)</label>
                    <input type="number" step="0.05" min="0.01" max="1" wire:model="beta" class="form-control">
                </div>
                @endif

                @if($method === 'seasonal_index')
                <div class="col-md-3">
                    <label class="form-label">Panjang Musim</label>
                    <input type="number" min="2" max="52" wire:model="seasonLength" class="form-control">
                </div>
                @endif

                <div class="col-md-3">
                    <label class="form-label">Safety Stock (%)</label>
                    <input type="number" step="0.05" min="0" max="1" wire:model="safetyPct" class="form-control">
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-primary" wire:click="run"><i class="bi bi-play-fill"></i> Jalankan Prediksi</button>
            </div>
        </div>
    </div>

    @if($hasRun)
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card content-card">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Grafik Aktual vs Prediksi</h6>
                        <canvas id="predChart" height="140"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card content-card h-100">
                    <div class="card-body">
                        <h6 class="fw-semibold">Evaluasi Error</h6>
                        <table class="table table-sm mb-3">
                            <tbody>
                                <tr><th>MAD</th><td class="text-end">{{ $metrics['mad'] ?? '—' }}</td></tr>
                                <tr><th>MSE</th><td class="text-end">{{ $metrics['mse'] ?? '—' }}</td></tr>
                                <tr><th>RMSE</th><td class="text-end">{{ $metrics['rmse'] ?? '—' }}</td></tr>
                                <tr><th>MAPE</th><td class="text-end">{{ $metrics['mape'] !== null ? number_format($metrics['mape'], 2).'%' : '—' }}</td></tr>
                            </tbody>
                        </table>
                        <h6 class="fw-semibold">Rekomendasi Stok</h6>
                        <dl class="row mb-0">
                            <dt class="col-7">Prediksi total</dt><dd class="col-5 text-end">{{ F::number($recommendation['forecast']) }}</dd>
                            <dt class="col-7">Safety stock ({{ $recommendation['safety_percentage'] }}%)</dt><dd class="col-5 text-end">{{ F::number($recommendation['safety_stock']) }}</dd>
                            <dt class="col-7 fw-bold">Rekomendasi</dt><dd class="col-5 text-end fw-bold text-success">{{ F::number($recommendation['recommended_stock']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="card content-card mt-3">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Kesimpulan</h6>
                <p class="mb-0">{{ $conclusion }}</p>
            </div>
        </div>

        <div class="card content-card mt-3">
            <div class="card-body">
                <h6 class="fw-semibold">Rincian Prediksi</h6>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead><tr><th>Periode</th><th class="text-end">Prediksi (qty)</th></tr></thead>
                        <tbody>
                        @foreach($forecast as $i => $v)
                            <tr>
                                <td>{{ $forecastPeriods[$i] ?? '—' }}</td>
                                <td class="text-end fw-semibold">{{ number_format($v, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="card content-card mt-3">
        <div class="card-body">
            <h6 class="fw-semibold">Riwayat Prediksi</h6>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Tanggal</th><th>Metode</th><th>Periode</th><th class="text-end">Prediksi</th><th class="text-end">MAPE</th><th class="text-end">RMSE</th></tr></thead>
                    <tbody>
                    @forelse($recentPredictions as $r)
                        <tr>
                            <td>{{ F::tanggal($r->prediction_date) }}</td>
                            <td>{{ \App\Services\PredictionService::METHODS[$r->method] ?? $r->method }}</td>
                            <td class="text-capitalize">{{ $r->period_type }}</td>
                            <td class="text-end">{{ number_format((float) $r->predicted_value, 2, ',', '.') }}</td>
                            <td class="text-end">{{ $r->mape !== null ? number_format((float) $r->mape, 2).'%' : '—' }}</td>
                            <td class="text-end">{{ $r->rmse !== null ? number_format((float) $r->rmse, 2) : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada prediksi tersimpan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let predChartInstance = null;
        function renderPredChart(payload) {
            const el = document.getElementById('predChart');
            if (!el || !payload) return;
            if (predChartInstance) predChartInstance.destroy();
            predChartInstance = new Chart(el, {
                type: 'line',
                data: {
                    labels: payload.labels,
                    datasets: [
                        { label: 'Aktual', data: payload.actual, borderColor: '#8f4e30', backgroundColor: 'rgba(143,78,48,0.15)', tension: .3, spanGaps: false },
                        { label: 'Prediksi', data: payload.predicted, borderColor: '#f4a7b9', backgroundColor: 'rgba(244,167,185,0.25)', borderDash: [6,4], tension: .3, spanGaps: true },
                    ],
                },
                options: { responsive: true, plugins: { legend: { labels: { boxWidth: 12 } } }, scales: { y: { beginAtZero: true } } },
            });
        }

        @if($hasRun)
        document.addEventListener('livewire:navigated', () => renderPredChart(@json($this->chartPayload)));
        document.addEventListener('DOMContentLoaded', () => renderPredChart(@json($this->chartPayload)));
        @endif

        window.addEventListener('prediction-updated', (e) => {
            const chart = e.detail?.[0]?.chart || e.detail?.chart;
            if (chart) renderPredChart(chart);
        });
    </script>
    @endpush
</div>
