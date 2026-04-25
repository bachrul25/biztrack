<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            @if(file_exists(public_path('assets/logo-boba.png')))
                                <img src="{{ asset('assets/logo-boba.png') }}" alt="PT BOBA" style="height: 80px;" class="mb-3">
                            @else
                                <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:80px;height:80px;">
                                    <i class="bi bi-shop fs-1 text-warning"></i>
                                </div>
                            @endif
                            <h4 class="fw-bold mt-2">Register Seller</h4>
                            <p class="text-muted small">Daftar sebagai mitra / seller PT BOBA</p>
                        </div>

                        <form wire:submit="register">
                            <h6 class="fw-bold text-muted mb-3"><i class="bi bi-person"></i> Data Pribadi</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror">
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="text" wire:model="phone" class="form-control @error('phone') is-invalid @enderror">
                                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Alamat</label>
                                    <input type="text" wire:model="address" class="form-control @error('address') is-invalid @enderror">
                                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" wire:model="password" class="form-control @error('password') is-invalid @enderror">
                                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <input type="password" wire:model="password_confirmation" class="form-control">
                                </div>
                            </div>

                            <hr>
                            <h6 class="fw-bold text-muted mb-3"><i class="bi bi-shop"></i> Data Toko</h6>
                            <div class="mb-3">
                                <label class="form-label">Nama Toko</label>
                                <input type="text" wire:model="shop_name" class="form-control @error('shop_name') is-invalid @enderror">
                                @error('shop_name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi Toko</label>
                                <textarea wire:model="shop_description" class="form-control @error('shop_description') is-invalid @enderror" rows="3"></textarea>
                                @error('shop_description') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat Toko</label>
                                <textarea wire:model="shop_address" class="form-control @error('shop_address') is-invalid @enderror" rows="2"></textarea>
                                @error('shop_address') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <button type="submit" class="btn btn-warning w-100 py-2 fw-semibold">
                                <i class="bi bi-shop me-1"></i> Daftar Sebagai Seller
                            </button>
                        </form>

                        <hr>
                        <div class="text-center">
                            <p class="mb-1">Sudah punya akun? <a href="/login" class="text-success fw-semibold">Login</a></p>
                            <a href="/" class="text-muted small"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
