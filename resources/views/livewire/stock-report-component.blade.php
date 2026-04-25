@php use App\Helpers\FormatHelper as F; @endphp
<div>
    <div class="page-header d-flex justify-content-between align-items-end flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Laporan Stok</h1>
            <p class="text-muted mb-0">Snapshot stok saat ini & pergerakan stok periode {{ F::tanggal($dateFrom) }} — {{ F::tanggal($dateTo) }}</p>
        </div>
        <button class="btn btn-outline-primary" wire:click="exportPdf"><i class="bi bi-file-earmark-pdf"></i> Export PDF</button>
    </div>

    <div class="card content-card mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-3"><label class="form-label small">Dari Tanggal</label><input type="date" wire:model.live="dateFrom" class="form-control"></div>
                <div class="col-md-3"><label class="form-label small">Sampai Tanggal</label><input type="date" wire:model.live="dateTo" class="form-control"></div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card content-card">
                <div class="card-body">
                    <h6 class="fw-semibold">Stok Saat Ini</h6>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead><tr><th>Kode</th><th>Produk</th><th>Kategori</th><th class="text-end">Stok</th><th class="text-end">Min</th></tr></thead>
                            <tbody>
                            @foreach($products as $p)
                                <tr>
                                    <td class="small text-muted">{{ $p->code }}</td>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ $p->category?->name ?? '—' }}</td>
                                    <td class="text-end"><span class="badge text-bg-{{ $p->stock_badge }}">{{ $p->stock }} {{ $p->unit }}</span></td>
                                    <td class="text-end">{{ $p->minimum_stock }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card content-card">
                <div class="card-body">
                    <h6 class="fw-semibold">Stok Menipis</h6>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead><tr><th>Produk</th><th class="text-end">Stok</th><th class="text-end">Min</th></tr></thead>
                            <tbody>
                            @forelse($low_stock as $p)
                                <tr>
                                    <td>{{ $p->name }}</td>
                                    <td class="text-end"><span class="badge text-bg-{{ $p->stock_badge }}">{{ $p->stock }}</span></td>
                                    <td class="text-end">{{ $p->minimum_stock }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">Stok aman.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card content-card mt-3">
        <div class="card-body">
            <h6 class="fw-semibold">Riwayat Pergerakan Stok</h6>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Tanggal</th><th>Produk</th><th>Tipe</th><th class="text-end">Qty</th><th>Keterangan</th></tr></thead>
                    <tbody>
                    @forelse($movements as $m)
                        <tr>
                            <td>{{ F::tanggal($m->movement_date) }}</td>
                            <td>{{ $m->product?->name }}</td>
                            <td><span class="badge text-bg-{{ $m->type === 'in' ? 'success' : 'danger' }}">{{ $m->type === 'in' ? 'Masuk' : 'Keluar' }}</span></td>
                            <td class="text-end">{{ $m->quantity }}</td>
                            <td>{{ $m->description }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada pergerakan stok pada periode ini.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
