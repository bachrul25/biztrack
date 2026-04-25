<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            @if(file_exists(public_path('assets/logo-boba.png')))
                                <img src="{{ asset('assets/logo-boba.png') }}" alt="PT BOBA" style="height: 80px;" class="mb-3">
                            @else
                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:80px;height:80px;">
                                    <i class="bi bi-building fs-1 text-success"></i>
                                </div>
                            @endif
                            <h4 class="fw-bold mt-2">Login PT BOBA</h4>
                            <p class="text-muted small">Masuk ke akun Anda</p>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form wire:submit="login">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                                </div>
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" wire:model="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                                </div>
                                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </button>
                        </form>

                        <hr>
                        <div class="text-center">
                            <p class="mb-1">Belum punya akun?</p>
                            <a href="/register" class="btn btn-outline-success btn-sm me-1">Register Buyer</a>
                            <a href="/seller/register" class="btn btn-outline-warning btn-sm">Register Seller</a>
                        </div>
                        <div class="text-center mt-3">
                            <a href="/" class="text-muted small"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
