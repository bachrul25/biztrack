@php
    $pdfUrl = route('reports.stocks.pdf', ['q' => $search, 'status' => $status]);
@endphp
<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h3 class="mb-1 fw-bold">Laporan Stok Produk</h3>
            <p class="text-muted mb-0">Ringkasan stok seluruh produk.</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i> Cetak</button>
            <a href="{{ $pdfUrl }}" target="_blank" class="btn btn-primary">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card content-card"><div class="card-body">
                <div class="text-muted small">Jumlah Produk</div>
                <div class="fs-4 fw-bold">{{ $totalProducts }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card content-card"><div class="card-body">
                <div class="text-muted small">Total Stok</div>
                <div class="fs-4 fw-bold">{{ number_format($totalStock, 0, ',', '.') }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card content-card"><div class="card-body">
                <div class="text-muted small">Stok Menipis</div>
                <div class="fs-4 fw-bold text-warning">{{ $lowCount }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card content-card"><div class="card-body">
                <div class="text-muted small">Stok Habis</div>
                <div class="fs-4 fw-bold text-danger">{{ $outCount }}</div>
            </div></div>
        </div>
    </div>

    <div class="card content-card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <input wire:model.live.debounce.400ms="search" type="text" class="form-control" placeholder="Cari produk...">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="status" class="form-select">
                        <option value="">Semua status</option>
                        <option value="safe">Stok Aman</option>
                        <option value="low">Stok Menipis</option>
                        <option value="out">Stok Habis</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Stok</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($products as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->category }}</td>
                            <td class="text-end">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                            <td class="text-end">{{ $p->stock }}</td>
                            <td><span class="badge text-bg-{{ $p->stock_badge }}">{{ ucfirst($p->stock_status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $products->links() }}</div>
        </div>
    </div>
</div>
