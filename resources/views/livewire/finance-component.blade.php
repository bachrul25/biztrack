@php
    $fmtIdr = fn ($v) => 'Rp ' . number_format((float) $v, 0, ',', '.');
@endphp
<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h3 class="mb-1 fw-bold">Manajemen Keuangan</h3>
            <p class="text-muted mb-0">Catat pemasukan & pengeluaran usaha.</p>
        </div>
        <button wire:click="openCreate" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Transaksi
        </button>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card content-card">
                <div class="card-body">
                    <div class="text-muted small">Total Pemasukan</div>
                    <div class="fs-5 fw-bold text-success">{{ $fmtIdr($totalIncome) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card content-card">
                <div class="card-body">
                    <div class="text-muted small">Total Pengeluaran</div>
                    <div class="fs-5 fw-bold text-danger">{{ $fmtIdr($totalExpense) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card content-card">
                <div class="card-body">
                    <div class="text-muted small">Laba Bersih</div>
                    <div class="fs-5 fw-bold {{ $netProfit >= 0 ? 'text-primary' : 'text-danger' }}">{{ $fmtIdr($netProfit) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card content-card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input wire:model.live.debounce.400ms="search" type="text" class="form-control" placeholder="Cari deskripsi/sumber...">
                    </div>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="typeFilter" class="form-select">
                        <option value="">Semua jenis</option>
                        <option value="income">Pemasukan</option>
                        <option value="expense">Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input wire:model.live="dateFrom" type="date" class="form-control">
                </div>
                <div class="col-md-3">
                    <input wire:model.live="dateTo" type="date" class="form-control">
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
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($finances as $f)
                        <tr wire:key="fin-{{ $f->id }}">
                            <td>{{ $f->date?->format('d M Y') }}</td>
                            <td>
                                @if($f->type === 'income')
                                    <span class="badge text-bg-success">Pemasukan</span>
                                @else
                                    <span class="badge text-bg-danger">Pengeluaran</span>
                                @endif
                            </td>
                            <td>{{ $f->description }}</td>
                            <td><span class="badge text-bg-light">{{ $f->source }}</span></td>
                            <td class="text-end fw-semibold {{ $f->type === 'income' ? 'text-success' : 'text-danger' }}">
                                {{ $fmtIdr($f->amount) }}
                            </td>
                            <td class="text-end">
                                <button wire:click="edit({{ $f->id }})" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDelete(() => @this.delete({{ $f->id }}))">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada transaksi keuangan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $finances->links() }}</div>
        </div>
    </div>

    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.45)">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit.prevent="store">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editMode ? 'Edit Transaksi Keuangan' : 'Tambah Transaksi Keuangan' }}</h5>
                            <button type="button" class="btn-close" wire:click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Jenis <span class="text-danger">*</span></label>
                                    <select wire:model="type" class="form-select @error('type') is-invalid @enderror">
                                        <option value="income">Pemasukan</option>
                                        <option value="expense">Pengeluaran</option>
                                    </select>
                                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                    <input wire:model="date" type="date" class="form-control @error('date') is-invalid @enderror">
                                    @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                                    <input wire:model="amount" type="number" step="0.01" min="0" class="form-control @error('amount') is-invalid @enderror">
                                    @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                    <input wire:model="description" type="text" class="form-control @error('description') is-invalid @enderror" placeholder="Misal: Pembelian tepung">
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Sumber / Kategori <span class="text-danger">*</span></label>
                                    <input wire:model="source" type="text" list="source-list" class="form-control @error('source') is-invalid @enderror">
                                    <datalist id="source-list">
                                        @foreach ($sources as $src)
                                            <option value="{{ $src }}">
                                        @endforeach
                                    </datalist>
                                    @error('source') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" wire:click="closeModal">Batal</button>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
