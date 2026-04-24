@php
    $fmtIdr = fn ($v) => 'Rp ' . number_format((float) $v, 0, ',', '.');
    $pdfUrl = route('reports.sales.pdf', ['dari' => $dateFrom, 'sampai' => $dateTo]);
@endphp
<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h3 class="mb-1 fw-bold">Laporan Penjualan</h3>
            <p class="text-muted mb-0">Ringkasan transaksi penjualan per periode.</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary">
                <i class="bi bi-printer"></i> Cetak
            </button>
            <a href="{{ $pdfUrl }}" target="_blank" class="btn btn-primary">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="card content-card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label small">Dari</label>
                    <input wire:model.live="dateFrom" type="date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Sampai</label>
                    <input wire:model.live="dateTo" type="date" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label small">Cari Invoice</label>
                    <input wire:model.live.debounce.400ms="search" type="text" class="form-control" placeholder="Nomor invoice...">
                </div>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3">
                        <div class="text-muted small">Total Transaksi</div>
                        <div class="fs-4 fw-bold">{{ $totalTransactions }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3">
                        <div class="text-muted small">Total Omzet</div>
                        <div class="fs-4 fw-bold text-primary">{{ $fmtIdr($totalAmount) }}</div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Invoice</th>
                            <th>Kasir</th>
                            <th class="text-end">Jumlah Item</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($sales as $s)
                        <tr>
                            <td>{{ $s->date?->format('d M Y') }}</td>
                            <td>{{ $s->invoice_number }}</td>
                            <td>{{ $s->user?->name }}</td>
                            <td class="text-end">{{ $s->details->sum('quantity') }}</td>
                            <td class="text-end fw-semibold">{{ $fmtIdr($s->total) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data pada periode ini.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $sales->links() }}</div>
        </div>
    </div>
</div>
