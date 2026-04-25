<div>
    @include('partials.buyer-navbar')
    <div class="container py-4">
        <a href="/buyer/services" class="btn btn-secondary btn-sm mb-3"><i class="bi bi-arrow-left me-1"></i>Kembali</a>

        <h4 class="fw-bold mb-4"><i class="bi bi-calendar-check me-2"></i>Booking Layanan</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Detail Layanan</h5>
                        <table class="table table-borderless">
                            <tr><td class="fw-bold">Nama:</td><td>{{ $service->name }}</td></tr>
                            <tr><td class="fw-bold">Jenis:</td><td><span class="badge bg-success">{{ str_replace('_', ' ', ucwords($service->service_type, '_')) }}</span></td></tr>
                            <tr><td class="fw-bold">Deskripsi:</td><td>{{ $service->description }}</td></tr>
                            <tr><td class="fw-bold">Harga:</td><td class="text-success fw-bold">Rp {{ number_format($service->price, 0, ',', '.') }}</td></tr>
                            <tr><td class="fw-bold">Seller:</td><td>{{ $service->seller->name ?? '-' }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Form Booking</h5>
                        <form wire:submit="book">
                            <div class="mb-3">
                                <label class="form-label">Kebutuhan / Requirement</label>
                                <textarea wire:model="requirement" class="form-control @error('requirement') is-invalid @enderror" rows="4" placeholder="Jelaskan kebutuhan Anda..."></textarea>
                                @error('requirement') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran</label>
                                <select wire:model="payment_method" class="form-select">
                                    <option value="transfer_bank">Transfer Bank</option>
                                    <option value="e_wallet">E-Wallet</option>
                                    <option value="cod">COD</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100 btn-lg">
                                <i class="bi bi-check-circle me-1"></i> Book & Bayar
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
