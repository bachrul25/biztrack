@php
    $fmtIdr = fn ($v) => 'Rp ' . number_format((float) $v, 0, ',', '.');
    $pdfUrl = route('reports.profit-loss.pdf', ['dari' => $dateFrom, 'sampai' => $dateTo]);
@endphp
<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h3 class="mb-1 fw-bold">Laporan Laba Rugi</h3>
            <p class="text-muted mb-0">Ringkasan laba / rugi usaha per periode.</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i> Cetak</button>
            <a href="{{ $pdfUrl }}" target="_blank" class="btn btn-primary"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
        </div>
    </div>

    <div class="card content-card mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label small">Dari</label>
                    <input wire:model.live="dateFrom" type="date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Sampai</label>
                    <input wire:model.live="dateTo" type="date" class="form-control">
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card content-card h-100">
                <div class="card-body">
                    <h6 class="fw-semibold text-success mb-3"><i class="bi bi-arrow-down-circle"></i> Pemasukan</h6>
                    <table class="table table-sm">
                        <thead><tr><th>Tanggal</th><th>Deskripsi</th><th class="text-end">Jumlah</th></tr></thead>
                        <tbody>
                        @forelse ($incomeRows as $row)
                            <tr>
                                <td>{{ $row->date?->format('d M Y') }}</td>
                                <td>{{ $row->description }}</td>
                                <td class="text-end">{{ $fmtIdr($row->amount) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada pemasukan.</td></tr>
                        @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold"><td colspan="2">Total Pemasukan</td><td class="text-end text-success">{{ $fmtIdr($totalIncome) }}</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card content-card h-100">
                <div class="card-body">
                    <h6 class="fw-semibold text-danger mb-3"><i class="bi bi-arrow-up-circle"></i> Pengeluaran</h6>
                    <table class="table table-sm">
                        <thead><tr><th>Tanggal</th><th>Deskripsi</th><th class="text-end">Jumlah</th></tr></thead>
                        <tbody>
                        @forelse ($expenseRows as $row)
                            <tr>
                                <td>{{ $row->date?->format('d M Y') }}</td>
                                <td>{{ $row->description }}</td>
                                <td class="text-end">{{ $fmtIdr($row->amount) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada pengeluaran.</td></tr>
                        @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold"><td colspan="2">Total Pengeluaran</td><td class="text-end text-danger">{{ $fmtIdr($totalExpense) }}</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card content-card mt-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <div class="text-muted small">Laba Bersih (Pemasukan − Pengeluaran)</div>
                <div class="fs-3 fw-bold {{ $netProfit >= 0 ? 'text-primary' : 'text-danger' }}">
                    {{ $fmtIdr($netProfit) }}
                </div>
            </div>
            <span class="badge {{ $netProfit >= 0 ? 'text-bg-primary' : 'text-bg-danger' }} fs-6">
                {{ $netProfit >= 0 ? 'LABA' : 'RUGI' }}
            </span>
        </div>
    </div>
</div>
