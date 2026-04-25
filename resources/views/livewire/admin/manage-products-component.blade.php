<div>
    @include('partials.admin-navbar')
    <div class="container-fluid py-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-box me-2"></i>Manage Products</h4>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <input type="text" wire:model.live="search" class="form-control" placeholder="Cari produk...">
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="brandFilter" class="form-select">
                            <option value="">Semua Brand</option>
                            <option value="tsoecha.co">tsoecha.co</option>
                            <option value="sokyuut">sokyuut</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="genderFilter" class="form-select">
                            <option value="">Semua Gender</option>
                            <option value="pria">Pria</option>
                            <option value="wanita">Wanita</option>
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
                                <th>Gambar</th>
                                <th>Nama</th>
                                <th>Brand</th>
                                <th>Gender</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Seller</th>
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
                                <td>{{ $product->seller->name ?? '-' }}</td>
                                <td><span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ $product->status }}</span></td>
                                <td>
                                    <button wire:click="toggleStatus({{ $product->id }})" class="btn btn-info btn-sm text-white"><i class="bi bi-arrow-repeat"></i></button>
                                    <button wire:click="deleteProduct({{ $product->id }})" wire:confirm="Yakin hapus produk ini?" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="10" class="text-center text-muted py-4">Belum ada produk.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
