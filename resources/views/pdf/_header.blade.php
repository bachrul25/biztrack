<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan' }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #3b2a24; }
        h1, h2, h3 { margin: 0; }
        .brand { color: #8f4e30; font-weight: 700; font-size: 20px; }
        .muted { color: #7a6a5e; }
        .header { border-bottom: 2px solid #8f4e30; padding-bottom: 8px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { padding: 6px 8px; border-bottom: 1px solid #ecdfd3; }
        thead th { background: #fff6ea; color: #6b3d2a; text-align: left; font-size: 10.5px; }
        .text-end { text-align: right; }
        .total-row { font-weight: bold; background: #fdf4ec; }
        .label-badge { padding: 2px 6px; border-radius: 4px; font-size: 10px; }
        .label-income { background: #dcfce7; color: #166534; }
        .label-expense { background: #fee2e2; color: #991b1b; }
        .summary-card { margin-top: 12px; padding: 10px; background: #fdf4ec; border-radius: 4px; }
        .footer { margin-top: 18px; text-align: center; color: #8a7a6d; font-size: 10px; }
        .no-border td, .no-border th { border: 0; }
    </style>
</head>
<body>
<div class="header">
    <table class="no-border">
        <tr>
            <td>
                <div class="brand">BizTrack</div>
                <div class="muted">Toko Kue Bu Nina</div>
            </td>
            <td style="text-align: right;">
                <div style="font-weight: bold">{{ $title }}</div>
                <div class="muted">
                    Periode:
                    {{ isset($dateFrom) && $dateFrom ? \Illuminate\Support\Carbon::parse($dateFrom)->format('d M Y') : '—' }}
                    s/d
                    {{ isset($dateTo) && $dateTo ? \Illuminate\Support\Carbon::parse($dateTo)->format('d M Y') : '—' }}
                </div>
                <div class="muted">Dicetak: {{ now()->format('d M Y H:i') }}</div>
            </td>
        </tr>
    </table>
</div>
