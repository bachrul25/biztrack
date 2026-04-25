<div>
    @include('partials.buyer-navbar')
    <div class="container py-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-cart me-2"></i>Keranjang Belanja</h4>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        @if($carts->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carts as $cart)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($cart->product->image)
                                            <img src="{{ asset('storage/' . $cart->product->image) }}" style="width:50px;height:50px;object-fit:cover;" class="rounded me-2">
                                        @endif
                                        <div>
                                            <strong>{{ $cart->product->name }}</strong><br>
                                            <small class="text-muted">{{ $cart->product->brand }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($cart->product->price, 0, ',', '.') }}</td>
                                <td style="width:120px;">
                                    <div class="input-group input-group-sm">
                                        <button wire:click="updateQuantity({{ $cart->id }}, {{ $cart->quantity - 1 }})" class="btn btn-outline-secondary">-</button>
                                        <input type="text" class="form-control text-center" value="{{ $cart->quantity }}" readonly>
                                        <button wire:click="updateQuantity({{ $cart->id }}, {{ $cart->quantity + 1 }})" class="btn btn-outline-secondary">+</button>
                                    </div>
                                </td>
                                <td class="fw-bold">Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</td>
                                <td>
                                    <button wire:click="removeItem({{ $cart->id }})" wire:confirm="Hapus item ini?" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-success">
                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                <td class="fw-bold fs-5">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="text-end mt-3">
                    <a href="/buyer/checkout" class="btn btn-success btn-lg px-5"><i class="bi bi-credit-card me-1"></i> Checkout</a>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-cart-x display-3"></i>
            <p class="mt-3">Keranjang belanja kosong.</p>
            <a href="/buyer/products" class="btn btn-success">Mulai Belanja</a>
        </div>
        @endif
    </div>
</div>
