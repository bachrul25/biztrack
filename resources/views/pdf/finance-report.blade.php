@php $fmtIdr = fn ($v) => 'Rp ' . number_format((float) $v, 0, ',', '.'); @endphp
@include('pdf._header', ['title' => 'Laporan Keuangan', 'dateFrom' => $dateFrom, 'dateTo' => $dateTo])

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Deskripsi</th>
            <th>Sumber</th>
            <th class="text-end">Jumlah</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($rows as $r)
        <tr>
            <td>{{ $r->date?->format('d M Y') }}</td>
            <td>
                <span class="label-badge {{ $r->type === 'income' ? 'label-income' : 'label-expense' }}">
                    {{ $r->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                </span>
            </td>
            <td>{{ $r->description }}</td>
            <td>{{ $r->source }}</td>
            <td class="text-end">{{ $fmtIdr($r->amount) }}</td>
        </tr>
    @empty
        <tr><td colspan="5" style="text-align:center; color:#888">Tidak ada data pada periode ini.</td></tr>
    @endforelse
    </tbody>
</table>

<div class="summary-card">
    <table class="no-border">
        <tr>
            <td><strong>Total Pemasukan</strong></td>
            <td class="text-end">{{ $fmtIdr($totalIncome) }}</td>
        </tr>
        <tr>
            <td><strong>Total Pengeluaran</strong></td>
            <td class="text-end">{{ $fmtIdr($totalExpense) }}</td>
        </tr>
        <tr>
            <td><strong>Laba Bersih</strong></td>
            <td class="text-end"><strong>{{ $fmtIdr($netProfit) }}</strong></td>
        </tr>
    </table>
</div>

@include('pdf._footer')
