@php use App\Helpers\FormatHelper as F; @endphp
<div>
    <div class="page-header d-flex justify-content-between align-items-end flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Keuangan</h1>
            <p class="text-muted mb-0">Catatan pemasukan dan pengeluaran usaha</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success" wire:click="openCreate('income')"><i class="bi bi-arrow-down-circle"></i> Tambah Pemasukan</button>
            <button class="btn btn-outline-danger" wire:click="openCreate('expense')"><i class="bi bi-arrow-up-circle"></i> Tambah Pengeluaran</button>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card stat-card p-3"><div class="text-muted small">Total Pemasukan</div><div class="fs-5 fw-bold text-success">{{ F::rupiah($totalIncome) }}</div></div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-3"><div class="text-muted small">Total Pengeluaran</div><div class="fs-5 fw-bold text-danger">{{ F::rupiah($totalExpense) }}</div></div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-3"><div class="text-muted small">Laba Bersih</div><div class="fs-5 fw-bold">{{ F::rupiah($totalIncome - $totalExpense) }}</div></div>
        </div>
    </div>

    <div class="card content-card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-md-4"><input type="text" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Cari keterangan / kategori…"></div>
                <div class="col-md-2">
                    <select wire:model.live="filterType" class="form-select">
                        <option value="">Semua tipe</option>
                        <option value="income">Pemasukan</option>
                        <option value="expense">Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-3"><input type="date" wire:model.live="dateFrom" class="form-control"></div>
                <div class="col-md-3"><input type="date" wire:model.live="dateTo" class="form-control"></div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr><th>Tanggal</th><th>Tipe</th><th>Kategori</th><th>Keterangan</th><th class="text-end">Jumlah</th><th class="text-end">Aksi</th></tr>
                    </thead>
                    <tbody>
                    @forelse($finances as $f)
                        <tr>
                            <td>{{ F::tanggal($f->transaction_date) }}</td>
                            <td><span class="badge text-bg-{{ $f->type === 'income' ? 'success' : 'danger' }}">{{ $f->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</span></td>
                            <td>{{ $f->category ?: '—' }}</td>
                            <td>
                                {{ $f->description }}
                                @if($f->source === 'sale')
                                    <span class="badge text-bg-light ms-1">Otomatis dari penjualan</span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold {{ $f->type === 'income' ? 'text-success' : 'text-danger' }}">{{ F::rupiah($f->amount) }}</td>
                            <td class="text-end text-nowrap">
                                @if($f->source !== 'sale')
                                    <button class="btn btn-sm btn-outline-primary" wire:click="openEdit({{ $f->id }})"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete(() => @this.delete({{ $f->id }}))"><i class="bi bi-trash"></i></button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data keuangan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $finances->links() }}</div>
        </div>
    </div>

    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.45);" wire:ignore.self>
        <div class="modal-dialog">
            <form wire:submit.prevent="save" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit' : 'Tambah' }} Data Keuangan</h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipe <span class="text-danger">*</span></label>
                            <select wire:model.live="type" class="form-select">
                                <option value="income">Pemasukan</option>
                                <option value="expense">Pengeluaran</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            @if($type === 'expense')
                                <select wire:model="category" class="form-select">
                                    @foreach($expenseCategories as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" wire:model="category" class="form-control" placeholder="Contoh: Penjualan manual, Donasi…">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                            <input type="text" wire:model="description" class="form-control @error('description') is-invalid @enderror">
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal</label>
                            <input type="date" wire:model="transaction_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" min="0" step="0.01" wire:model="amount" class="form-control @error('amount') is-invalid @enderror">
                            @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="$set('showModal', false)">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
