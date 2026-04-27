@php use App\Helpers\FormatHelper as F; @endphp
@include('pdf._header', ['title' => 'Laporan Keuangan', 'dateFrom' => $dateFrom, 'dateTo' => $dateTo])

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Tipe</th>
            <th>Kategori</th>
            <th>Keterangan</th>
            <th class="text-end">Jumlah</th>
        </tr>
    </thead>
    <tbody>
    @forelse($records as $i => $r)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ F::tanggal($r->transaction_date) }}</td>
            <td><span class="label-badge {{ $r->type === 'income' ? 'label-income' : 'label-expense' }}">{{ $r->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</span></td>
            <td>{{ $r->category }}</td>
            <td>{{ $r->description }}</td>
            <td class="text-end">{{ F::rupiah($r->amount) }}</td>
        </tr>
    @empty
        <tr><td colspan="6" style="text-align:center; color:#888">Tidak ada data</td></tr>
    @endforelse
    </tbody>
</table>

<div class="summary-card">
    <table class="no-border">
        <tr><td>Total Pemasukan</td><td class="text-end"><strong>{{ F::rupiah($total_income) }}</strong></td></tr>
        <tr><td>Total Pengeluaran</td><td class="text-end"><strong>{{ F::rupiah($total_expense) }}</strong></td></tr>
        <tr><td>Laba Bersih</td><td class="text-end"><strong>{{ F::rupiah($net) }}</strong></td></tr>
    </table>
</div>

@include('pdf._footer')
