<div>
    @include('partials.admin-navbar')
    <div class="container-fluid py-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-shop me-2"></i>Manage Sellers</h4>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Cari seller...">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Toko</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sellers as $seller)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $seller->name }}</td>
                                <td>{{ $seller->email }}</td>
                                <td>{{ $seller->sellerProfile->shop_name ?? '-' }}</td>
                                <td>
                                    @if($seller->sellerProfile)
                                        <span class="badge {{ match($seller->sellerProfile->status) { 'approved' => 'bg-success', 'pending' => 'bg-warning', 'rejected' => 'bg-danger' } }}">{{ $seller->sellerProfile->status }}</span>
                                    @else
                                        <span class="badge bg-secondary">No Profile</span>
                                    @endif
                                </td>
                                <td>
                                    @if($seller->sellerProfile && $seller->sellerProfile->status === 'pending')
                                        <button wire:click="approve({{ $seller->sellerProfile->id }})" class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i> Approve</button>
                                        <button wire:click="reject({{ $seller->sellerProfile->id }})" class="btn btn-danger btn-sm"><i class="bi bi-x-lg"></i> Reject</button>
                                    @endif
                                    <button wire:click="deleteSeller({{ $seller->id }})" wire:confirm="Yakin hapus seller ini?" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada seller.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $sellers->links() }}
            </div>
        </div>
    </div>
</div>
