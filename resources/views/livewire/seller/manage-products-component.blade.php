<div>
    @include('partials.seller-navbar')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0"><i class="bi bi-box me-2"></i>Manage Products</h4>
            <button wire:click="openModal" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Produk</button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Gambar</th>
                                <th>Nama</th>
                                <th>Brand</th>
                                <th>Gender</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" style="width:45px;height:45px;object-fit:cover;" class="rounded">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width:45px;height:45px;"><i class="bi bi-image text-muted"></i></div>
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td><span class="badge {{ $product->brand === 'tsoecha.co' ? 'bg-primary' : 'bg-danger' }}">{{ $product->brand }}</span></td>
                                <td>{{ $product->gender_category }}</td>
                                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td>{{ $product->stock }}</td>
                                <td><span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ $product->status }}</span></td>
                                <td>
                                    <button wire:click="edit({{ $product->id }})" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
                                    <button wire:click="toggleStatus({{ $product->id }})" class="btn btn-info btn-sm text-white"><i class="bi bi-arrow-repeat"></i></button>
                                    <button wire:click="delete({{ $product->id }})" wire:confirm="Yakin hapus?" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="text-center text-muted py-4">Belum ada produk.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $products->links() }}
            </div>
        </div>
    </div>

    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editId ? 'Edit' : 'Tambah' }} Produk</h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit="save">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Brand</label>
                                <select wire:model.live="brand" class="form-select">
                                    <option value="tsoecha.co">tsoecha.co (Pria)</option>
                                    <option value="sokyuut">sokyuut (Wanita)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender Category</label>
                                <select wire:model="gender_category" class="form-select">
                                    <option value="pria">Pria</option>
                                    <option value="wanita">Wanita</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea wire:model="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" wire:model="price" class="form-control @error('price') is-invalid @enderror">
                                @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" wire:model="stock" class="form-control @error('stock') is-invalid @enderror">
                                @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kategori</label>
                                <input type="text" wire:model="category" class="form-control @error('category') is-invalid @enderror" placeholder="e.g. Kaos, Kemeja">
                                @error('category') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gambar</label>
                                <input type="file" wire:model="image" class="form-control" accept="image/*">
                                @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select wire:model="status" class="form-select">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
