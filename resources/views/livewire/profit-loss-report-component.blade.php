@php use App\Helpers\FormatHelper as F; @endphp
<div>
    <div class="page-header d-flex justify-content-between align-items-end flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Laporan Laba Rugi</h1>
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

    <div class="card content-card">
        <div class="card-body">
            <table class="table align-middle">
                <tbody>
                    <tr><td>Total Penjualan (Pendapatan)</td><td class="text-end fw-semibold">{{ F::rupiah($revenue) }}</td></tr>
                    <tr><td>Total Modal Produk Terjual (HPP)</td><td class="text-end text-danger">({{ F::rupiah($cogs) }})</td></tr>
                    <tr class="table-light"><th>Laba Kotor</th><th class="text-end">{{ F::rupiah($gross_profit) }}</th></tr>
                    <tr><td>Total Pengeluaran Operasional</td><td class="text-end text-danger">({{ F::rupiah($total_expense) }})</td></tr>
                    <tr class="table-warning"><th>Laba Bersih</th><th class="text-end {{ $net_profit >= 0 ? 'text-success' : 'text-danger' }}">{{ F::rupiah($net_profit) }}</th></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
