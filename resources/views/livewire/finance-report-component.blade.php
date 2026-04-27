@php use App\Helpers\FormatHelper as F; @endphp
<div>
    <div class="page-header d-flex justify-content-between align-items-end flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Laporan Keuangan</h1>
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
        <div class="col-md-4"><div class="card stat-card p-3"><div class="text-muted small">Total Pemasukan</div><div class="fs-5 fw-bold text-success">{{ F::rupiah($total_income) }}</div></div></div>
        <div class="col-md-4"><div class="card stat-card p-3"><div class="text-muted small">Total Pengeluaran</div><div class="fs-5 fw-bold text-danger">{{ F::rupiah($total_expense) }}</div></div></div>
        <div class="col-md-4"><div class="card stat-card p-3"><div class="text-muted small">Laba Bersih</div><div class="fs-5 fw-bold {{ $net >= 0 ? 'text-success' : 'text-danger' }}">{{ F::rupiah($net) }}</div></div></div>
    </div>

    <div class="card content-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Tanggal</th><th>Tipe</th><th>Kategori</th><th>Keterangan</th><th class="text-end">Jumlah</th></tr></thead>
                    <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td>{{ F::tanggal($r->transaction_date) }}</td>
                            <td><span class="badge text-bg-{{ $r->type === 'income' ? 'success' : 'danger' }}">{{ $r->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</span></td>
                            <td>{{ $r->category ?: '—' }}</td>
                            <td>{{ $r->description }}</td>
                            <td class="text-end fw-semibold {{ $r->type === 'income' ? 'text-success' : 'text-danger' }}">{{ F::rupiah($r->amount) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data pada periode ini.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
