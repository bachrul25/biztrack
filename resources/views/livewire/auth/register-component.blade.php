<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            @if(file_exists(public_path('assets/logo-boba.png')))
                                <img src="{{ asset('assets/logo-boba.png') }}" alt="PT BOBA" style="height: 80px;" class="mb-3">
                            @else
                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:80px;height:80px;">
                                    <i class="bi bi-person-plus fs-1 text-success"></i>
                                </div>
                            @endif
                            <h4 class="fw-bold mt-2">Register Buyer</h4>
                            <p class="text-muted small">Daftar akun baru sebagai pembeli</p>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form wire:submit="register">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama lengkap">
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" wire:model="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="No. Telepon">
                                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea wire:model="address" class="form-control @error('address') is-invalid @enderror" rows="2" placeholder="Alamat lengkap"></textarea>
                                @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" wire:model="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 6 karakter">
                                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <input type="password" wire:model="password_confirmation" class="form-control" placeholder="Ulangi password">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                                <i class="bi bi-person-check me-1"></i> Daftar Sekarang
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
