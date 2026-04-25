<div>
    @include('partials.buyer-navbar')
    <div class="container py-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-credit-card me-2"></i>Checkout</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-md-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>
                        @foreach($carts as $cart)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <strong>{{ $cart->product->name }}</strong> x {{ $cart->quantity }}
                                <br><small class="text-muted">{{ $cart->product->brand }}</small>
                            </div>
                            <span class="fw-bold">Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        <div class="d-flex justify-content-between align-items-center pt-3">
                            <h5 class="fw-bold mb-0">Total</h5>
                            <h5 class="fw-bold text-success mb-0">Rp {{ number_format($total, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Detail Pembayaran</h5>
                        <form wire:submit="checkout">
                            <div class="mb-3">
                                <label class="form-label">Alamat Pengiriman</label>
                                <textarea wire:model="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                                @error('shipping_address') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran</label>
                                <select wire:model="payment_method" class="form-select">
                                    <option value="transfer_bank">Transfer Bank</option>
                                    <option value="e_wallet">E-Wallet</option>
                                    <option value="cod">COD (Cash on Delivery)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100 btn-lg">
                                <i class="bi bi-check-circle me-1"></i> Bayar Sekarang
                            </button>
                            @if($paymentFailed)
                            <button type="submit" class="btn btn-warning w-100 mt-2">
                                <i class="bi bi-arrow-clockwise me-1"></i> Retry Payment
                            </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
