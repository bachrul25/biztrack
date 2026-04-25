<div>
    @include('partials.buyer-navbar')
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <a href="/buyer/products" class="btn btn-secondary btn-sm mb-3"><i class="bi bi-arrow-left me-1"></i>Kembali</a>

        <div class="row g-4">
            <div class="col-md-5">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded shadow" alt="{{ $product->name }}">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height:400px;">
                        <i class="bi bi-image text-muted" style="font-size:4rem;"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-7">
                <div class="d-flex gap-2 mb-2">
                    <span class="badge {{ $product->brand === 'tsoecha.co' ? 'bg-primary' : 'bg-danger' }}">{{ $product->brand }}</span>
                    <span class="badge bg-secondary">{{ $product->gender_category }}</span>
                    <span class="badge bg-info">{{ $product->category }}</span>
                </div>
                <h3 class="fw-bold">{{ $product->name }}</h3>
                <h4 class="text-success fw-bold my-3">Rp {{ number_format($product->price, 0, ',', '.') }}</h4>
                <p class="text-muted">{{ $product->description }}</p>
                <div class="mb-3">
                    <span class="me-3"><i class="bi bi-box-seam me-1"></i> Stok: <strong>{{ $product->stock }}</strong></span>
                    <span><i class="bi bi-shop me-1"></i> Seller: <strong>{{ $product->seller->name ?? '-' }}</strong></span>
                </div>
                <button wire:click="addToCart" class="btn btn-success btn-lg px-5">
                    <i class="bi bi-cart-plus me-1"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>
