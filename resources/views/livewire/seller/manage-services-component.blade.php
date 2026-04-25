<div>
    @include('partials.seller-navbar')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0"><i class="bi bi-gear me-2"></i>Manage Services (tos2bro)</h4>
            <button wire:click="openModal" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Layanan</button>
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
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $service)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $service->name }}</td>
                                <td><span class="badge bg-success">{{ str_replace('_', ' ', ucwords($service->service_type, '_')) }}</span></td>
                                <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                                <td><span class="badge {{ $service->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ $service->status }}</span></td>
                                <td>
                                    <button wire:click="edit({{ $service->id }})" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
                                    <button wire:click="toggleStatus({{ $service->id }})" class="btn btn-info btn-sm text-white"><i class="bi bi-arrow-repeat"></i></button>
                                    <button wire:click="delete({{ $service->id }})" wire:confirm="Yakin hapus?" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada layanan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $services->links() }}
            </div>
        </div>
    </div>

    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editId ? 'Edit' : 'Tambah' }} Layanan tos2bro</h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit="save">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Jenis Layanan</label>
                            <select wire:model="service_type" class="form-select">
                                <option value="pengambilan_sampah">Pengambilan Sampah</option>
                                <option value="pengelolaan_sampah">Pengelolaan Sampah</option>
                                <option value="pengolahan_sampah_organik">Pengolahan Sampah Organik</option>
                                <option value="bahan_bakar_kendaraan">Bahan Bakar Kendaraan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Layanan</label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea wire:model="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" wire:model="price" class="form-control @error('price') is-invalid @enderror">
                                @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori</label>
                                <input type="text" wire:model="category" class="form-control" placeholder="Optional">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gambar</label>
                                <input type="file" wire:model="image" class="form-control" accept="image/*">
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
