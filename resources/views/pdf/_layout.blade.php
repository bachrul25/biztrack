<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan' }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
        h1, h2, h3 { margin: 0; }
        .brand { color: #6c4ae6; font-weight: 700; font-size: 20px; }
        .muted { color: #666; }
        .header { border-bottom: 2px solid #6c4ae6; padding-bottom: 8px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; }
        thead th { background: #f3f1fb; color: #3d2f7a; text-align: left; font-size: 10.5px; }
        .text-end { text-align: right; }
        .total-row { font-weight: bold; background: #f8f7fe; }
        .label-badge { padding: 2px 6px; border-radius: 4px; font-size: 10px; }
        .label-income { background: #dcfce7; color: #166534; }
        .label-expense { background: #fee2e2; color: #991b1b; }
        .summary { margin-top: 14px; }
        .summary td { padding: 6px 10px; }
        .footer { margin-top: 18px; text-align: center; color: #888; font-size: 10px; }
    </style>
</head>
<body>
<div class="header">
    <table style="border: 0;">
        <tr>
            <td style="border: 0;">
                <div class="brand">BizTrack</div>
                <div class="muted">Toko Kue Bu Nina</div>
            </td>
            <td style="border: 0; text-align: right;">
                <div style="font-weight: bold">{{ $title }}</div>
                <div class="muted">
                    Periode:
                    {{ $dateFrom ? \Illuminate\Support\Carbon::parse($dateFrom)->format('d M Y') : '—' }}
                    s/d
                    {{ $dateTo ? \Illuminate\Support\Carbon::parse($dateTo)->format('d M Y') : '—' }}
                </div>
                <div class="muted">Dicetak: {{ now()->format('d M Y H:i') }}</div>
            </td>
        </tr>
    </table>
</div>

{{ $slot }}

<div class="footer">© {{ date('Y') }} BizTrack — Business Tracking System • Toko Kue Bu Nina</div>
</body>
</html>
