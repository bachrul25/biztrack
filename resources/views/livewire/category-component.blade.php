<div>
    <div class="page-header d-flex justify-content-between align-items-end flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Kategori Produk</h1>
            <p class="text-muted mb-0">Kelola kategori produk kue</p>
        </div>
        <button class="btn btn-primary" wire:click="openCreate">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
        </button>
    </div>

    <div class="card content-card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-md-5">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Cari kategori…">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th class="text-end">Jumlah Produk</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $c)
                        <tr>
                            <td class="fw-semibold">{{ $c->name }}</td>
                            <td class="text-muted">{{ $c->description ?: '—' }}</td>
                            <td class="text-end"><span class="badge text-bg-light">{{ $c->products_count }}</span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" wire:click="openEdit({{ $c->id }})"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDelete(() => @this.delete({{ $c->id }}))">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $categories->links() }}</div>
        </div>
    </div>

    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.45);" wire:ignore.self>
        <div class="modal-dialog">
            <form wire:submit.prevent="save" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit' : 'Tambah' }} Kategori</h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Deskripsi</label>
                        <textarea wire:model="description" class="form-control" rows="3"></textarea>
                        @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
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
