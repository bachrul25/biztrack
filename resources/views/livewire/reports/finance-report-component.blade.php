@php
    $fmtIdr = fn ($v) => 'Rp ' . number_format((float) $v, 0, ',', '.');
    $pdfUrl = route('reports.finance.pdf', ['dari' => $dateFrom, 'sampai' => $dateTo, 'type' => $typeFilter]);
@endphp
<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h3 class="mb-1 fw-bold">Laporan Keuangan</h3>
            <p class="text-muted mb-0">Semua transaksi pemasukan & pengeluaran.</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i> Cetak</button>
            <a href="{{ $pdfUrl }}" target="_blank" class="btn btn-primary">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card content-card"><div class="card-body">
                <div class="text-muted small">Pemasukan</div>
                <div class="fs-5 fw-bold text-success">{{ $fmtIdr($totalIncome) }}</div>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card content-card"><div class="card-body">
                <div class="text-muted small">Pengeluaran</div>
                <div class="fs-5 fw-bold text-danger">{{ $fmtIdr($totalExpense) }}</div>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card content-card"><div class="card-body">
                <div class="text-muted small">Laba Bersih</div>
                <div class="fs-5 fw-bold {{ $netProfit >= 0 ? 'text-primary' : 'text-danger' }}">{{ $fmtIdr($netProfit) }}</div>
            </div></div>
        </div>
    </div>

    <div class="card content-card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-md-3"><input wire:model.live="dateFrom" type="date" class="form-control"></div>
                <div class="col-md-3"><input wire:model.live="dateTo" type="date" class="form-control"></div>
                <div class="col-md-3">
                    <select wire:model.live="typeFilter" class="form-select">
                        <option value="">Semua jenis</option>
                        <option value="income">Pemasukan</option>
                        <option value="expense">Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input wire:model.live.debounce.400ms="search" type="text" class="form-control" placeholder="Cari deskripsi...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Deskripsi</th>
                            <th>Sumber</th>
                            <th class="text-end">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($finances as $f)
                        <tr>
                            <td>{{ $f->date?->format('d M Y') }}</td>
                            <td>
                                @if($f->type === 'income')
                                    <span class="badge text-bg-success">Pemasukan</span>
                                @else
                                    <span class="badge text-bg-danger">Pengeluaran</span>
                                @endif
                            </td>
                            <td>{{ $f->description }}</td>
                            <td>{{ $f->source }}</td>
                            <td class="text-end fw-semibold {{ $f->type === 'income' ? 'text-success' : 'text-danger' }}">
                                {{ $fmtIdr($f->amount) }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $finances->links() }}</div>
        </div>
    </div>
</div>
