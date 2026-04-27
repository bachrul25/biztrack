@php use App\Helpers\FormatHelper as F; @endphp
<div>
    <div class="page-header d-flex justify-content-between align-items-end flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Laporan Penjualan</h1>
            <p class="text-muted mb-0">Periode: {{ F::tanggal($dateFrom) }} — {{ F::tanggal($dateTo) }}</p>
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

    <div class="row g-3 mb-3">
        <div class="col-md-3"><div class="card stat-card p-3"><div class="text-muted small">Total Transaksi</div><div class="fs-5 fw-bold">{{ F::number($total_transactions) }}</div></div></div>
        <div class="col-md-3"><div class="card stat-card p-3"><div class="text-muted small">Total Produk Terjual</div><div class="fs-5 fw-bold">{{ F::number($total_qty) }}</div></div></div>
        <div class="col-md-3"><div class="card stat-card p-3"><div class="text-muted small">Total Pendapatan</div><div class="fs-5 fw-bold text-success">{{ F::rupiah($total_revenue) }}</div></div></div>
        <div class="col-md-3"><div class="card stat-card p-3"><div class="text-muted small">Laba Kotor</div><div class="fs-5 fw-bold">{{ F::rupiah($gross_profit) }}</div></div></div>
    </div>

    <div class="card content-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Invoice</th><th>Tanggal</th><th>Kasir</th><th class="text-end">Qty</th><th class="text-end">Total</th><th class="text-end">Laba</th></tr></thead>
                    <tbody>
                    @forelse($sales as $s)
                        <tr>
                            <td>{{ $s->invoice_number }}</td>
                            <td>{{ F::tanggal($s->sale_date) }}</td>
                            <td>{{ $s->user?->name }}</td>
                            <td class="text-end">{{ $s->items->sum('quantity') }}</td>
                            <td class="text-end">{{ F::rupiah($s->total_amount) }}</td>
                            <td class="text-end text-success">{{ F::rupiah($s->gross_profit) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada transaksi pada periode ini.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
