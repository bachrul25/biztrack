@php
    $fmtIdr = fn ($v) => 'Rp ' . number_format((float) $v, 0, ',', '.');
@endphp
<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h3 class="mb-1 fw-bold">Manajemen Produk</h3>
            <p class="text-muted mb-0">Kelola produk kue yang dijual di Toko Kue Bu Nina.</p>
        </div>
        <button wire:click="openCreate" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Produk
        </button>
    </div>

    <div class="card content-card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-md-6 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input wire:model.live.debounce.400ms="search" type="text"
                               class="form-control" placeholder="Cari nama produk atau kategori...">
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <select wire:model.live="category" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th style="width: 64px">#</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Stok</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($products as $p)
                        <tr wire:key="prod-{{ $p->id }}">
                            <td>{{ $loop->iteration + ($products->firstItem() ? $products->firstItem() - 1 : 0) }}</td>
                            <td>
                                @if($p->image)
                                    <img src="{{ asset('storage/' . $p->image) }}" alt="" width="48" height="48" class="rounded object-fit-cover border">
                                @else
                                    <span class="d-inline-block bg-light rounded" style="width:48px;height:48px;line-height:48px;text-align:center">
                                        <i class="bi bi-image text-muted"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $p->name }}</td>
                            <td><span class="badge text-bg-light">{{ $p->category }}</span></td>
                            <td class="text-end">{{ $fmtIdr($p->price) }}</td>
                            <td class="text-end">
                                <span class="badge text-bg-{{ $p->stock_badge }}">{{ $p->stock }}</span>
                            </td>
                            <td>
                                @if($p->status === 'active')
                                    <span class="badge text-bg-success">Aktif</span>
                                @else
                                    <span class="badge text-bg-secondary">Non-aktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button wire:click="edit({{ $p->id }})" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDelete(() => @this.delete({{ $p->id }}))">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada produk.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-2">{{ $products->links() }}</div>
        </div>
    </div>

    {{-- Modal form --}}
    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.45)">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form wire:submit.prevent="store">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                {{ $editMode ? 'Edit Produk' : 'Tambah Produk' }}
                            </h5>
                            <button type="button" class="btn-close" wire:click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                    <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select wire:model="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Non-aktif</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                    <input wire:model="price" type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror">
                                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Stok <span class="text-danger">*</span></label>
                                    <input wire:model="stock" type="number" min="0" class="form-control @error('stock') is-invalid @enderror">
                                    @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <input wire:model="categoryForm" type="text" list="kategori-list" class="form-control @error('categoryForm') is-invalid @enderror" placeholder="Misal: Kue Kering">
                                    <datalist id="kategori-list">
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat }}">
                                        @endforeach
                                    </datalist>
                                    @error('categoryForm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Foto Produk</label>
                                    <input wire:model="image" type="file" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        @if ($image)
                                            <img src="{{ $image->temporaryUrl() }}" width="64" height="64" class="rounded object-fit-cover border">
                                            <span class="text-muted small">Preview</span>
                                        @elseif ($existingImage)
                                            <img src="{{ asset('storage/' . $existingImage) }}" width="64" height="64" class="rounded object-fit-cover border">
                                            <span class="text-muted small">Foto saat ini</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" wire:click="closeModal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
