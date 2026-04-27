@php use App\Helpers\FormatHelper as F; @endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $sale->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #3b2a24; }
        .brand { color: #8f4e30; font-weight: 700; font-size: 20px; }
        .muted { color: #7a6a5e; }
        .header { border-bottom: 2px solid #8f4e30; padding-bottom: 8px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        .items th, .items td { padding: 6px 8px; border-bottom: 1px solid #ecdfd3; }
        .items thead th { background: #fff6ea; text-align: left; }
        .text-end { text-align: right; }
        .no-border td, .no-border th { border: 0; }
        .summary td { padding: 4px 8px; }
        .total-row { font-weight: bold; background: #fdf4ec; }
        .footer { margin-top: 20px; text-align: center; color: #8a7a6d; font-size: 10px; }
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
                <td style="text-align:right">
                    <div style="font-weight:bold; font-size:14px">INVOICE</div>
                    <div>{{ $sale->invoice_number }}</div>
                    <div class="muted">{{ F::tanggalJam($sale->created_at) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="no-border" style="margin-bottom:8px">
        <tr>
            <td>Kasir: <strong>{{ $sale->user?->name }}</strong></td>
            <td class="text-end">Metode: <strong>{{ strtoupper($sale->payment_method) }}</strong></td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th class="text-end">Qty</th>
                <th class="text-end">Harga</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
        @foreach($sale->items as $i => $it)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $it->product?->name }}</td>
                <td class="text-end">{{ $it->quantity }}</td>
                <td class="text-end">{{ F::rupiah($it->price) }}</td>
                <td class="text-end">{{ F::rupiah($it->subtotal) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <table class="no-border summary" style="margin-top:10px; width: 50%; margin-left: auto;">
        <tr class="total-row"><td>Total</td><td class="text-end">{{ F::rupiah($sale->total_amount) }}</td></tr>
        <tr><td>Dibayar</td><td class="text-end">{{ F::rupiah($sale->paid_amount) }}</td></tr>
        <tr><td>Kembalian</td><td class="text-end">{{ F::rupiah($sale->change_amount) }}</td></tr>
    </table>

    <div class="footer">
        Terima kasih atas kunjungan Anda • Toko Kue Bu Nina
    </div>
</body>
</html>
