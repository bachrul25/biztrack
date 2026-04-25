<div>
    @include('partials.admin-navbar')
    <div class="container-fluid py-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-gear me-2"></i>Manage Services</h4>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <input type="text" wire:model.live="search" class="form-control" placeholder="Cari layanan...">
                    </div>
                    <div class="col-md-3">
                        <select wire:model.live="serviceTypeFilter" class="form-select">
                            <option value="">Semua Jenis</option>
                            <option value="pengambilan_sampah">Pengambilan Sampah</option>
                            <option value="pengelolaan_sampah">Pengelolaan Sampah</option>
                            <option value="pengolahan_sampah_organik">Pengolahan Sampah Organik</option>
                            <option value="bahan_bakar_kendaraan">Bahan Bakar Kendaraan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="statusFilter" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Harga</th>
                                <th>Seller</th>
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
                                <td>{{ $service->seller->name ?? '-' }}</td>
                                <td><span class="badge {{ $service->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ $service->status }}</span></td>
                                <td>
                                    <button wire:click="toggleStatus({{ $service->id }})" class="btn btn-info btn-sm text-white"><i class="bi bi-arrow-repeat"></i></button>
                                    <button wire:click="deleteService({{ $service->id }})" wire:confirm="Yakin hapus layanan ini?" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada layanan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $services->links() }}
            </div>
        </div>
    </div>
</div>
