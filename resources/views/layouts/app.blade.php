<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'BizTrack' }} — BizTrack</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bt-primary: #6c4ae6;
            --bt-primary-dark: #5538c9;
            --bt-bg: #f4f6fb;
        }
        body {
            background: var(--bt-bg);
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }
        .app-sidebar {
            width: 248px;
            min-height: 100vh;
            background: linear-gradient(180deg, #1f1b3d 0%, #2a2457 100%);
            color: #d8d6f0;
            position: fixed;
            top: 0;
            left: 0;
            padding: 1.25rem 0.75rem;
            z-index: 1030;
        }
        .app-sidebar .brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.35rem;
            letter-spacing: .5px;
            padding: .25rem .75rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
            margin-bottom: .75rem;
        }
        .app-sidebar .nav-section {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: rgba(255,255,255,.35);
            padding: 1rem .75rem .25rem;
        }
        .app-sidebar .nav-link {
            color: #cfcde9;
            border-radius: .5rem;
            padding: .55rem .75rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .92rem;
            margin-bottom: .1rem;
        }
        .app-sidebar .nav-link:hover {
            background: rgba(255,255,255,.06);
            color: #fff;
        }
        .app-sidebar .nav-link.active {
            background: var(--bt-primary);
            color: #fff;
            box-shadow: 0 4px 14px rgba(108,74,230,.35);
        }
        .app-main {
            margin-left: 248px;
            min-height: 100vh;
        }
        .app-navbar {
            background: #fff;
            border-bottom: 1px solid rgba(0,0,0,.05);
            padding: .75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        .app-content {
            padding: 1.5rem;
        }
        .stat-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 6px 18px rgba(20, 20, 52, .05);
        }
        .stat-card .icon {
            width: 48px;
            height: 48px;
            border-radius: .75rem;
            display: grid;
            place-items: center;
            font-size: 1.3rem;
            color: #fff;
        }
        .card.content-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 6px 18px rgba(20, 20, 52, .05);
        }
        .table thead th {
            background: #f7f8fc;
            font-weight: 600;
            color: #4a4a68;
            font-size: .85rem;
        }
        .btn-primary {
            background-color: var(--bt-primary);
            border-color: var(--bt-primary);
        }
        .btn-primary:hover,
        .btn-primary:focus {
            background-color: var(--bt-primary-dark);
            border-color: var(--bt-primary-dark);
        }
        @media (max-width: 991.98px) {
            .app-sidebar {
                transform: translateX(-100%);
                transition: transform .25s ease;
            }
            .app-sidebar.show {
                transform: translateX(0);
            }
            .app-main {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
    @livewireStyles
</head>
<body>
@auth
    @include('partials.sidebar')
@endauth

<div class="@auth app-main @endauth">
    @auth
        @include('partials.navbar')
    @endauth

    <main class="@auth app-content @else py-5 @endauth">
        {{ $slot }}
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('sidebarToggle');
        if (toggle) {
            toggle.addEventListener('click', () => {
                document.querySelector('.app-sidebar')?.classList.toggle('show');
            });
        }
    });

    window.addEventListener('swal', (e) => {
        const detail = e.detail?.[0] || e.detail || {};
        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            icon: detail.icon || 'success',
            title: detail.title || 'Berhasil',
        });
    });

    window.confirmDelete = function (callback, message) {
        Swal.fire({
            title: 'Yakin hapus data ini?',
            text: message || 'Tindakan ini tidak bisa dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) callback();
        });
    };
</script>
@livewireScripts
@stack('scripts')
</body>
</html>
