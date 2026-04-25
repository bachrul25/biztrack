<div>
    @include('partials.buyer-navbar')
    <div class="container py-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-bag me-2"></i>Produk Fashion</h4>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <div class="row g-2 mb-4">
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
                <select wire:model.live="categoryFilter" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row g-3">
            @forelse($products as $product)
            <div class="col-lg-3 col-md-4 col-6">
                <div class="card border-0 shadow-sm card-hover h-100">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="height:200px;object-fit:cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height:200px;">
                            <i class="bi bi-image text-muted" style="font-size:2.5rem;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="badge {{ $product->brand === 'tsoecha.co' ? 'bg-primary' : 'bg-danger' }} small">{{ $product->brand }}</span>
                            <span class="badge bg-secondary small">{{ $product->gender_category }}</span>
                        </div>
                        <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                        <small class="text-muted">{{ $product->category }}</small>
                        <h6 class="text-success fw-bold mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</h6>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                        <div class="d-flex gap-1">
                            <a href="/buyer/products/{{ $product->id }}" class="btn btn-info btn-sm text-white flex-fill"><i class="bi bi-eye"></i> Detail</a>
                            <button wire:click="addToCart({{ $product->id }})" class="btn btn-success btn-sm flex-fill"><i class="bi bi-cart-plus"></i> Cart</button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-5">
                <i class="bi bi-bag-x display-4"></i>
                <p class="mt-2">Tidak ada produk ditemukan.</p>
            </div>
            @endforelse
        </div>
        <div class="mt-4">{{ $products->links() }}</div>
    </div>
</div>
