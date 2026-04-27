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
            --bt-primary: #b86b47;       /* warm brown */
            --bt-primary-dark: #8f4e30;
            --bt-pink: #f4a7b9;           /* soft pink */
            --bt-cream: #fff6ea;
            --bt-bg: #fdf8f2;             /* cream background */
            --bt-sidebar-1: #4a2a1a;
            --bt-sidebar-2: #6b3d2a;
        }
        body {
            background: var(--bt-bg);
            font-family: 'Segoe UI', Tahoma, sans-serif;
            color: #3b2a24;
        }
        .app-sidebar {
            width: 248px;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--bt-sidebar-1) 0%, var(--bt-sidebar-2) 100%);
            color: #f2e6da;
            position: fixed;
            top: 0;
            left: 0;
            padding: 1.25rem 0.75rem;
            z-index: 1030;
            overflow-y: auto;
        }
        .app-sidebar .brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.35rem;
            letter-spacing: .5px;
            padding: .25rem .75rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
            margin-bottom: .75rem;
        }
        .app-sidebar .brand small {
            display: block;
            font-weight: 400;
            font-size: .7rem;
            color: var(--bt-pink);
            letter-spacing: 0;
        }
        .app-sidebar .nav-section {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: rgba(255,255,255,.45);
            padding: 1rem .75rem .25rem;
        }
        .app-sidebar .nav-link {
            color: #e8d5c5;
            border-radius: .5rem;
            padding: .55rem .75rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .92rem;
            margin-bottom: .1rem;
        }
        .app-sidebar .nav-link:hover {
            background: rgba(255,255,255,.07);
            color: #fff;
        }
        .app-sidebar .nav-link.active {
            background: var(--bt-primary);
            color: #fff;
            box-shadow: 0 4px 14px rgba(184,107,71,.45);
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
            box-shadow: 0 6px 18px rgba(70, 40, 20, .05);
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
            box-shadow: 0 6px 18px rgba(70, 40, 20, .05);
        }
        .table thead th {
            background: var(--bt-cream);
            font-weight: 600;
            color: #6b4a3a;
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
        .btn-outline-primary {
            color: var(--bt-primary);
            border-color: var(--bt-primary);
        }
        .btn-outline-primary:hover {
            background-color: var(--bt-primary);
            border-color: var(--bt-primary);
        }
        a { color: var(--bt-primary-dark); }
        .page-header h1 { color: #4a2a1a; }
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
        const { icon = 'success', title = 'Berhasil', text = '' } = detail;
        Swal.fire({
            icon, title, text,
            timer: icon === 'success' ? 1800 : undefined,
            showConfirmButton: icon !== 'success',
            toast: icon === 'success',
            position: icon === 'success' ? 'top-end' : 'center',
        });
    });

    window.confirmDelete = function (callback, message) {
        Swal.fire({
            title: 'Yakin?',
            text: message || 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#b86b47',
        }).then((r) => {
            if (r.isConfirmed) callback();
        });
    };
</script>

@livewireScripts
@stack('scripts')
</body>
</html>
