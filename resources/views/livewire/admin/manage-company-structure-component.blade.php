<div>
    @include('partials.admin-navbar')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>Manage Company Structure</h4>
            <button wire:click="openModal" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Data</button>
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
                                <th>Urutan</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($structures as $s)
                            <tr>
                                <td>{{ $s->sort_order }}</td>
                                <td>
                                    @if($s->photo)
                                        <img src="{{ asset('storage/' . $s->photo) }}" class="rounded-circle" style="width:45px;height:45px;object-fit:cover;">
                                    @else
                                        <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:45px;height:45px;">
                                            <i class="bi bi-person text-secondary"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $s->name }}</td>
                                <td>{{ $s->position }}</td>
                                <td>
                                    <span class="badge {{ $s->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ $s->status }}</span>
                                </td>
                                <td>
                                    <button wire:click="edit({{ $s->id }})" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
                                    <button wire:click="toggleStatus({{ $s->id }})" class="btn btn-info btn-sm text-white"><i class="bi bi-arrow-repeat"></i></button>
                                    <button wire:click="delete({{ $s->id }})" wire:confirm="Yakin hapus data ini?" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $structures->links() }}
            </div>
        </div>
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editId ? 'Edit' : 'Tambah' }} Struktur Perusahaan</h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit="save">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" wire:model="position" class="form-control @error('position') is-invalid @enderror">
                            @error('position') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea wire:model="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto</label>
                            <input type="file" wire:model="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                            @error('photo') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Urutan</label>
                                <input type="number" wire:model="sort_order" class="form-control">
                            </div>
                            <div class="col-6 mb-3">
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
