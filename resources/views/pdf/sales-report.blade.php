@php $fmtIdr = fn ($v) => 'Rp ' . number_format((float) $v, 0, ',', '.'); @endphp
@include('pdf._header', ['title' => 'Laporan Penjualan', 'dateFrom' => $dateFrom, 'dateTo' => $dateTo])

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Invoice</th>
            <th>Kasir</th>
            <th class="text-end">Item</th>
            <th class="text-end">Total</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($sales as $s)
        <tr>
            <td>{{ $s->date?->format('d M Y') }}</td>
            <td>{{ $s->invoice_number }}</td>
            <td>{{ $s->user?->name }}</td>
            <td class="text-end">{{ $s->details->sum('quantity') }}</td>
            <td class="text-end">{{ $fmtIdr($s->total) }}</td>
        </tr>
    @empty
        <tr><td colspan="5" style="text-align:center; color:#888">Tidak ada data pada periode ini.</td></tr>
    @endforelse
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="4">Total ({{ $totalTransactions }} transaksi)</td>
            <td class="text-end">{{ $fmtIdr($totalAmount) }}</td>
        </tr>
    </tfoot>
</table>

@include('pdf._footer')
