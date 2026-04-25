@php use App\Helpers\FormatHelper as F; @endphp
@include('pdf._header', ['title' => 'Laporan Laba Rugi', 'dateFrom' => $dateFrom, 'dateTo' => $dateTo])

<table>
    <tbody>
        <tr><td>Total Penjualan (Pendapatan)</td><td class="text-end">{{ F::rupiah($revenue) }}</td></tr>
        <tr><td>Total Modal Produk Terjual (HPP)</td><td class="text-end">({{ F::rupiah($cogs) }})</td></tr>
        <tr class="total-row"><td><strong>Laba Kotor</strong></td><td class="text-end"><strong>{{ F::rupiah($gross_profit) }}</strong></td></tr>
        <tr><td>Total Pengeluaran Operasional</td><td class="text-end">({{ F::rupiah($total_expense) }})</td></tr>
        <tr class="total-row"><td><strong>Laba Bersih</strong></td><td class="text-end"><strong>{{ F::rupiah($net_profit) }}</strong></td></tr>
    </tbody>
</table>

<div class="summary-card">
    {{ $net_profit >= 0
        ? 'Usaha memperoleh laba bersih pada periode ini sebesar ' . F::rupiah($net_profit) . '.'
        : 'Usaha mengalami kerugian sebesar ' . F::rupiah(abs($net_profit)) . ' pada periode ini.' }}
</div>

@include('pdf._footer')
