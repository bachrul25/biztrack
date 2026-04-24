<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — BizTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #6c4ae6 0%, #3f2db0 100%);
            display: flex;
            align-items: center;
        }
        .login-card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 20px 40px rgba(0,0,0,.25);
        }
        .brand {
            color: #6c4ae6;
            font-weight: 700;
            letter-spacing: .5px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <div class="card login-card">
                <div class="card-body p-4 p-lg-5">
                    <div class="text-center mb-4">
                        <div class="brand fs-3 mb-1">
                            <i class="bi bi-graph-up-arrow"></i> BizTrack
                        </div>
                        <p class="text-muted mb-0 small">Toko Kue Bu Nina</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger py-2">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="admin@biztrack.com" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Password</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="••••••••" required>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">Ingat saya</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" style="background:#6c4ae6;border-color:#6c4ae6">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </button>
                    </form>

                    <hr class="my-4">
                    <div class="small text-muted">
                        <div><strong>Admin demo:</strong> admin@biztrack.com / password</div>
                        <div><strong>Owner demo:</strong> owner@biztrack.com / password</div>
                    </div>
                </div>
            </div>
            <p class="text-center text-white-50 small mt-3 mb-0">© {{ date('Y') }} BizTrack</p>
        </div>
    </div>
</div>
</body>
</html>
