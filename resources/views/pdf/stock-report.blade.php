@php $fmtIdr = fn ($v) => 'Rp ' . number_format((float) $v, 0, ',', '.'); @endphp
@include('pdf._header', ['title' => 'Laporan Stok Produk', 'dateFrom' => null, 'dateTo' => null])

<table>
    <thead>
        <tr>
            <th>Produk</th>
            <th>Kategori</th>
            <th class="text-end">Harga</th>
            <th class="text-end">Stok</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($products as $p)
        <tr>
            <td>{{ $p->name }}</td>
            <td>{{ $p->category }}</td>
            <td class="text-end">{{ $fmtIdr($p->price) }}</td>
            <td class="text-end">{{ $p->stock }}</td>
            <td>{{ ucfirst($p->stock_status) }}</td>
        </tr>
    @empty
        <tr><td colspan="5" style="text-align:center; color:#888">Tidak ada produk.</td></tr>
    @endforelse
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="3">Total ({{ $totalProducts }} produk)</td>
            <td class="text-end">{{ number_format($totalStock, 0, ',', '.') }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>

@include('pdf._footer')
