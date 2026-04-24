<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h3 class="mb-1 fw-bold">Manajemen Stok</h3>
            <p class="text-muted mb-0">Pantau & sesuaikan stok produk.</p>
        </div>
    </div>

    <div class="card content-card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-md-6 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input wire:model.live.debounce.400ms="search" type="text"
                               class="form-control" placeholder="Cari produk...">
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <select wire:model.live="filter" class="form-select">
                        <option value="">Semua status</option>
                        <option value="safe">Stok Aman</option>
                        <option value="low">Stok Menipis</option>
                        <option value="out">Stok Habis</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th class="text-end">Stok</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($products as $p)
                        <tr wire:key="stock-{{ $p->id }}">
                            <td class="fw-semibold">{{ $p->name }}</td>
                            <td><span class="badge text-bg-light">{{ $p->category }}</span></td>
                            <td class="text-end">{{ $p->stock }}</td>
                            <td>
                                <span class="badge text-bg-{{ $p->stock_badge }}">
                                    {{ ucfirst($p->stock_status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button wire:click="edit({{ $p->id }})" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square"></i> Update Stok
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada produk.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $products->links() }}</div>
        </div>
    </div>

    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.45)">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit.prevent="update">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Stok — {{ $productName }}</h5>
                            <button type="button" class="btn-close" wire:click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Stok Baru</label>
                            <input wire:model="stock" type="number" min="0"
                                   class="form-control @error('stock') is-invalid @enderror">
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Stok tidak boleh minus.</div>
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
