@php use App\Helpers\FormatHelper as F; @endphp
@include('pdf._header', ['title' => 'Laporan Stok', 'dateFrom' => $dateFrom, 'dateTo' => $dateTo])

<h3 style="margin-top:10px;">Stok Saat Ini</h3>
<table>
    <thead><tr><th>No</th><th>Kode</th><th>Produk</th><th>Kategori</th><th class="text-end">Stok</th><th class="text-end">Min</th></tr></thead>
    <tbody>
    @foreach($products as $i => $p)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $p->code }}</td>
            <td>{{ $p->name }}</td>
            <td>{{ $p->category?->name ?? '-' }}</td>
            <td class="text-end">{{ $p->stock }} {{ $p->unit }}</td>
            <td class="text-end">{{ $p->minimum_stock }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h3 style="margin-top:14px;">Produk Stok Menipis</h3>
<table>
    <thead><tr><th>Produk</th><th class="text-end">Stok</th><th class="text-end">Minimum</th></tr></thead>
    <tbody>
    @forelse($low_stock as $p)
        <tr><td>{{ $p->name }}</td><td class="text-end">{{ $p->stock }}</td><td class="text-end">{{ $p->minimum_stock }}</td></tr>
    @empty
        <tr><td colspan="3" style="text-align:center; color:#888">Stok aman</td></tr>
    @endforelse
    </tbody>
</table>

<h3 style="margin-top:14px;">Pergerakan Stok</h3>
<table>
    <thead><tr><th>Tanggal</th><th>Produk</th><th>Tipe</th><th class="text-end">Qty</th><th>Keterangan</th></tr></thead>
    <tbody>
    @forelse($movements as $m)
        <tr>
            <td>{{ F::tanggal($m->movement_date) }}</td>
            <td>{{ $m->product?->name }}</td>
            <td>{{ $m->type === 'in' ? 'Masuk' : 'Keluar' }}</td>
            <td class="text-end">{{ $m->quantity }}</td>
            <td>{{ $m->description }}</td>
        </tr>
    @empty
        <tr><td colspan="5" style="text-align:center; color:#888">Tidak ada pergerakan stok</td></tr>
    @endforelse
    </tbody>
</table>

@include('pdf._footer')
