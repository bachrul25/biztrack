<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PT Bikin Orang Bahagia' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @livewireStyles
    <style>
        :root {
            --boba-primary: #2c3e50;
            --boba-secondary: #27ae60;
            --boba-accent: #e67e22;
            --boba-light: #ecf0f1;
        }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .btn-boba { background: var(--boba-secondary); color: #fff; border: none; }
        .btn-boba:hover { background: #219a52; color: #fff; }
        .bg-boba { background: var(--boba-primary); }
        .text-boba { color: var(--boba-secondary); }
        .navbar-brand img { height: 40px; }
        .hero-section {
            background: linear-gradient(135deg, var(--boba-primary) 0%, #1a252f 100%);
            color: #fff;
            padding: 100px 0;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }
        .card-hover { transition: all 0.3s ease; }
        .footer { background: var(--boba-primary); color: #ccc; padding: 60px 0 30px; }
        .footer a { color: #aaa; text-decoration: none; }
        .footer a:hover { color: var(--boba-secondary); }
    </style>
    @stack('styles')
</head>
<body>
    {{ $slot }}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
