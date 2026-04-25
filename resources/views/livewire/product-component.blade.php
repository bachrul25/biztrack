@php use App\Helpers\FormatHelper as F; @endphp
<div>
    <div class="page-header d-flex justify-content-between align-items-end flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Produk</h1>
            <p class="text-muted mb-0">Kelola katalog produk kue</p>
        </div>
        <button class="btn btn-primary" wire:click="openCreate">
            <i class="bi bi-plus-lg"></i> Tambah Produk
        </button>
    </div>

    <div class="card content-card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-md-5">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Cari nama / kode produk…">
                </div>
                <div class="col-md-4">
                    <select wire:model.live="filterCategory" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th class="text-end">Harga Modal</th>
                            <th class="text-end">Harga Jual</th>
                            <th class="text-end">Stok</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($products as $p)
                        <tr>
                            <td>
                                @if($p->image)
                                    <img src="{{ asset('storage/'.$p->image) }}" class="rounded" style="width:42px;height:42px;object-fit:cover">
                                @else
                                    <div class="rounded bg-light d-grid place-items-center" style="width:42px;height:42px">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $p->code }}</td>
                            <td class="fw-semibold">{{ $p->name }}</td>
                            <td>{{ $p->category?->name ?? '—' }}</td>
                            <td class="text-end">{{ F::rupiah($p->cost_price) }}</td>
                            <td class="text-end">{{ F::rupiah($p->selling_price) }}</td>
                            <td class="text-end">
                                <span class="badge text-bg-{{ $p->stock_badge }}">{{ $p->stock }} {{ $p->unit }}</span>
                            </td>
                            <td>
                                <span class="badge text-bg-{{ $p->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ $p->status === 'active' ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                <button class="btn btn-sm btn-outline-secondary" wire:click="openDetail({{ $p->id }})"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-primary" wire:click="openEdit({{ $p->id }})"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDelete(() => @this.delete({{ $p->id }}))">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">Belum ada produk.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $products->links() }}</div>
        </div>
    </div>

    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.45);" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <form wire:submit.prevent="save" class="modal-content" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit' : 'Tambah' }} Produk</h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kode <span class="text-danger">*</span></label>
                            <input type="text" wire:model="code" class="form-control @error('code') is-invalid @enderror">
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kategori</label>
                            <select wire:model="category_id" class="form-select">
                                <option value="">— Pilih —</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Harga Modal <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" wire:model="cost_price" class="form-control @error('cost_price') is-invalid @enderror">
                            @error('cost_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" wire:model="selling_price" class="form-control @error('selling_price') is-invalid @enderror">
                            @error('selling_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Satuan</label>
                            <input type="text" wire:model="unit" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stok Awal</label>
                            <input type="number" min="0" wire:model="stock" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Minimum Stok</label>
                            <input type="number" min="0" wire:model="minimum_stock" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select wire:model="status" class="form-select">
                                <option value="active">Aktif</option>
                                <option value="inactive">Non-aktif</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Deskripsi</label>
                            <textarea wire:model="description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Foto Produk</label>
                            <input type="file" wire:model="imageUpload" class="form-control" accept="image/*">
                            @error('imageUpload') <div class="text-danger small">{{ $message }}</div> @enderror
                            @if ($imageUpload)
                                <img src="{{ $imageUpload->temporaryUrl() }}" class="mt-2 rounded" style="width:100px;height:100px;object-fit:cover">
                            @elseif($existingImage)
                                <img src="{{ asset('storage/'.$existingImage) }}" class="mt-2 rounded" style="width:100px;height:100px;object-fit:cover">
                            @endif
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

    @if($showDetail && $detail)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.45);" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Produk</h5>
                    <button type="button" class="btn-close" wire:click="$set('showDetail', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        @if($detail->image)
                            <img src="{{ asset('storage/'.$detail->image) }}" class="rounded" style="max-width:180px;max-height:180px;object-fit:cover">
                        @endif
                    </div>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Nama</dt><dd class="col-sm-8">{{ $detail->name }}</dd>
                        <dt class="col-sm-4">Kode</dt><dd class="col-sm-8">{{ $detail->code }}</dd>
                        <dt class="col-sm-4">Kategori</dt><dd class="col-sm-8">{{ $detail->category?->name ?? '—' }}</dd>
                        <dt class="col-sm-4">Harga Modal</dt><dd class="col-sm-8">{{ F::rupiah($detail->cost_price) }}</dd>
                        <dt class="col-sm-4">Harga Jual</dt><dd class="col-sm-8">{{ F::rupiah($detail->selling_price) }}</dd>
                        <dt class="col-sm-4">Stok</dt><dd class="col-sm-8">{{ $detail->stock }} {{ $detail->unit }}</dd>
                        <dt class="col-sm-4">Minimum Stok</dt><dd class="col-sm-8">{{ $detail->minimum_stock }} {{ $detail->unit }}</dd>
                        <dt class="col-sm-4">Status</dt><dd class="col-sm-8">{{ $detail->status === 'active' ? 'Aktif' : 'Non-aktif' }}</dd>
                        @if($detail->description)
                            <dt class="col-sm-4">Deskripsi</dt><dd class="col-sm-8">{{ $detail->description }}</dd>
                        @endif
                    </dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="$set('showDetail', false)">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
