@php use App\Helpers\FormatHelper as F; @endphp
@include('pdf._header', ['title' => 'Laporan Penjualan', 'dateFrom' => $dateFrom, 'dateTo' => $dateTo])

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Invoice</th>
            <th>Tanggal</th>
            <th>Kasir</th>
            <th class="text-end">Qty</th>
            <th class="text-end">Total</th>
            <th class="text-end">Modal</th>
            <th class="text-end">Laba</th>
        </tr>
    </thead>
    <tbody>
    @forelse($sales as $i => $s)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $s->invoice_number }}</td>
            <td>{{ F::tanggal($s->sale_date) }}</td>
            <td>{{ $s->user?->name }}</td>
            <td class="text-end">{{ $s->items->sum('quantity') }}</td>
            <td class="text-end">{{ F::rupiah($s->total_amount) }}</td>
            <td class="text-end">{{ F::rupiah($s->total_cost) }}</td>
            <td class="text-end">{{ F::rupiah($s->gross_profit) }}</td>
        </tr>
    @empty
        <tr><td colspan="8" style="text-align:center; color:#888">Tidak ada transaksi</td></tr>
    @endforelse
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="4">Total</td>
            <td class="text-end">{{ $total_qty }}</td>
            <td class="text-end">{{ F::rupiah($total_revenue) }}</td>
            <td class="text-end">{{ F::rupiah($total_cost) }}</td>
            <td class="text-end">{{ F::rupiah($gross_profit) }}</td>
        </tr>
    </tfoot>
</table>

<div class="summary-card">
    <strong>Ringkasan:</strong>
    Total transaksi {{ $total_transactions }} • Total produk terjual {{ $total_qty }} • Laba kotor {{ F::rupiah($gross_profit) }}
</div>

@include('pdf._footer')
