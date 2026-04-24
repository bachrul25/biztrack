@php $fmtIdr = fn ($v) => 'Rp ' . number_format((float) $v, 0, ',', '.'); @endphp
@include('pdf._header', ['title' => 'Laporan Laba Rugi', 'dateFrom' => $dateFrom, 'dateTo' => $dateTo])

<h3 style="color:#166534; margin-top:12px">Pemasukan</h3>
<table>
    <thead>
        <tr><th>Tanggal</th><th>Deskripsi</th><th>Sumber</th><th class="text-end">Jumlah</th></tr>
    </thead>
    <tbody>
    @forelse ($incomeRows as $r)
        <tr>
            <td>{{ $r->date?->format('d M Y') }}</td>
            <td>{{ $r->description }}</td>
            <td>{{ $r->source }}</td>
            <td class="text-end">{{ $fmtIdr($r->amount) }}</td>
        </tr>
    @empty
        <tr><td colspan="4" style="text-align:center; color:#888">Tidak ada pemasukan.</td></tr>
    @endforelse
    </tbody>
    <tfoot>
        <tr class="total-row"><td colspan="3">Total Pemasukan</td><td class="text-end">{{ $fmtIdr($totalIncome) }}</td></tr>
    </tfoot>
</table>

<h3 style="color:#991b1b; margin-top:16px">Pengeluaran</h3>
<table>
    <thead>
        <tr><th>Tanggal</th><th>Deskripsi</th><th>Sumber</th><th class="text-end">Jumlah</th></tr>
    </thead>
    <tbody>
    @forelse ($expenseRows as $r)
        <tr>
            <td>{{ $r->date?->format('d M Y') }}</td>
            <td>{{ $r->description }}</td>
            <td>{{ $r->source }}</td>
            <td class="text-end">{{ $fmtIdr($r->amount) }}</td>
        </tr>
    @empty
        <tr><td colspan="4" style="text-align:center; color:#888">Tidak ada pengeluaran.</td></tr>
    @endforelse
    </tbody>
    <tfoot>
        <tr class="total-row"><td colspan="3">Total Pengeluaran</td><td class="text-end">{{ $fmtIdr($totalExpense) }}</td></tr>
    </tfoot>
</table>

<div class="summary-card">
    <table class="no-border">
        <tr>
            <td style="font-size: 13px;"><strong>LABA BERSIH</strong> ({{ $netProfit >= 0 ? 'LABA' : 'RUGI' }})</td>
            <td class="text-end" style="font-size: 14px; color: {{ $netProfit >= 0 ? '#3d2f7a' : '#991b1b' }}">
                <strong>{{ $fmtIdr($netProfit) }}</strong>
            </td>
        </tr>
    </table>
</div>

@include('pdf._footer')
