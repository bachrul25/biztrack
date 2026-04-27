@php use App\Helpers\FormatHelper as F; @endphp
<div>
    @if($mode === 'list')
        <div class="page-header d-flex justify-content-between align-items-end flex-wrap mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Transaksi Penjualan</h1>
                <p class="text-muted mb-0">Riwayat transaksi dan POS</p>
            </div>
            <button class="btn btn-primary" wire:click="startCreate">
                <i class="bi bi-plus-lg"></i> Transaksi Baru
            </button>
        </div>

        <div class="card content-card">
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <input type="text" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Cari nomor invoice…">
                    </div>
                    <div class="col-md-3"><input type="date" wire:model.live="dateFrom" class="form-control"></div>
                    <div class="col-md-3"><input type="date" wire:model.live="dateTo" class="form-control"></div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>Kasir</th>
                                <th>Metode</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Laba</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($sales as $s)
                            <tr>
                                <td class="fw-semibold">{{ $s->invoice_number }}</td>
                                <td>{{ F::tanggal($s->sale_date) }}</td>
                                <td>{{ $s->user?->name }}</td>
                                <td class="text-capitalize">{{ $s->payment_method }}</td>
                                <td class="text-end">{{ F::rupiah($s->total_amount) }}</td>
                                <td class="text-end text-success">{{ F::rupiah($s->gross_profit) }}</td>
                                <td class="text-end text-nowrap">
                                    <button class="btn btn-sm btn-outline-secondary" wire:click="openDetail({{ $s->id }})"><i class="bi bi-eye"></i></button>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('sales.invoice', $s) }}" target="_blank"><i class="bi bi-printer"></i></a>
                                    <button class="btn btn-sm btn-outline-danger"
                                            onclick="confirmDelete(() => @this.delete({{ $s->id }}), 'Stok akan dikembalikan dan pemasukan akan dihapus.')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">{{ $sales->links() }}</div>
            </div>
        </div>
    @endif

    @if($mode === 'create')
        <div class="page-header d-flex justify-content-between align-items-end flex-wrap mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Transaksi Baru</h1>
                <p class="text-muted mb-0">Tambahkan produk ke keranjang dan selesaikan pembayaran</p>
            </div>
            <button class="btn btn-light" wire:click="backToList"><i class="bi bi-arrow-left"></i> Kembali</button>
        </div>

        <div class="row g-3">
            <div class="col-lg-7">
                <div class="card content-card">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Pilih Produk</h6>
                        <input type="text" wire:model.live.debounce.300ms="productSearch" class="form-control mb-3" placeholder="Cari produk…">
                        <div class="row g-2" style="max-height: 450px; overflow-y: auto">
                        @foreach($products as $p)
                            <div class="col-md-6">
                                <button type="button" class="w-100 text-start border rounded p-2 bg-white {{ $p->stock <= 0 ? 'opacity-50' : '' }}"
                                        style="cursor:pointer"
                                        @if($p->stock <= 0) disabled @endif
                                        wire:click="addToCart({{ $p->id }})">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($p->image)
                                            <img src="{{ asset('storage/'.$p->image) }}" class="rounded" style="width:44px;height:44px;object-fit:cover">
                                        @else
                                            <div class="rounded bg-light d-grid place-items-center" style="width:44px;height:44px"><i class="bi bi-image text-muted"></i></div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold small">{{ $p->name }}</div>
                                            <div class="small text-muted">Stok {{ $p->stock }} {{ $p->unit }}</div>
                                        </div>
                                        <div class="text-end fw-semibold small">{{ F::rupiah($p->selling_price) }}</div>
                                    </div>
                                </button>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card content-card">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Keranjang</h6>
                        @if(empty($cart))
                            <div class="text-center text-muted py-4">Keranjang masih kosong</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm align-middle mb-2">
                                    <thead>
                                        <tr><th>Produk</th><th class="text-end">Qty</th><th class="text-end">Subtotal</th><th></th></tr>
                                    </thead>
                                    <tbody>
                                    @foreach($cart as $i => $row)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold small">{{ $row['name'] }}</div>
                                                <div class="small text-muted">{{ F::rupiah($row['price']) }}</div>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-secondary" wire:click="decrementQty({{ $i }})">−</button>
                                                    <span class="btn btn-light disabled">{{ $row['quantity'] }}</span>
                                                    <button type="button" class="btn btn-outline-secondary" wire:click="incrementQty({{ $i }})">+</button>
                                                </div>
                                            </td>
                                            <td class="text-end small">{{ F::rupiah($row['subtotal']) }}</td>
                                            <td><button type="button" class="btn btn-sm btn-link text-danger" wire:click="removeFromCart({{ $i }})"><i class="bi bi-x-lg"></i></button></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <hr>
                        <div class="d-flex justify-content-between">
                            <span>Total</span>
                            <span class="fw-bold fs-5">{{ F::rupiah($this->total) }}</span>
                        </div>

                        <div class="mt-3">
                            <label class="form-label small">Metode Pembayaran</label>
                            <select wire:model.live="paymentMethod" class="form-select">
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>

                        @if($paymentMethod === 'cash')
                        <div class="mt-2">
                            <label class="form-label small">Uang Dibayar</label>
                            <input type="number" min="0" step="1000" wire:model.live="paidAmount" class="form-control @error('paidAmount') is-invalid @enderror">
                            @error('paidAmount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="d-flex justify-content-between mt-2">
                                <span class="small text-muted">Kembalian</span>
                                <span class="fw-semibold">{{ F::rupiah($this->change) }}</span>
                            </div>
                        </div>
                        @endif

                        <div class="mt-2">
                            <label class="form-label small">Tanggal Transaksi</label>
                            <input type="date" wire:model="saleDate" class="form-control">
                        </div>

                        <button type="button" class="btn btn-primary w-100 mt-3" wire:click="checkout" @if(empty($cart)) disabled @endif>
                            <i class="bi bi-check2-circle"></i> Simpan Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($mode === 'detail' && $detail)
        <div class="page-header d-flex justify-content-between align-items-end flex-wrap mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Detail Transaksi</h1>
                <p class="text-muted mb-0">{{ $detail->invoice_number }} • {{ F::tanggalJam($detail->created_at) }}</p>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-primary" href="{{ route('sales.invoice', $detail) }}" target="_blank"><i class="bi bi-printer"></i> Cetak Invoice</a>
                <button class="btn btn-light" wire:click="backToList"><i class="bi bi-arrow-left"></i> Kembali</button>
            </div>
        </div>
        <div class="card content-card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Kasir</div>
                        <div class="fw-semibold">{{ $detail->user?->name }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small">Metode Pembayaran</div>
                        <div class="fw-semibold text-capitalize">{{ $detail->payment_method }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small">Tanggal Penjualan</div>
                        <div class="fw-semibold">{{ F::tanggal($detail->sale_date) }}</div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr><th>Produk</th><th class="text-end">Qty</th><th class="text-end">Harga</th><th class="text-end">Subtotal</th><th class="text-end">Laba</th></tr>
                        </thead>
                        <tbody>
                        @foreach($detail->items as $it)
                            <tr>
                                <td>{{ $it->product?->name }}</td>
                                <td class="text-end">{{ $it->quantity }}</td>
                                <td class="text-end">{{ F::rupiah($it->price) }}</td>
                                <td class="text-end">{{ F::rupiah($it->subtotal) }}</td>
                                <td class="text-end text-success">{{ F::rupiah($it->profit) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-semibold"><td colspan="3" class="text-end">Total</td><td class="text-end">{{ F::rupiah($detail->total_amount) }}</td><td class="text-end text-success">{{ F::rupiah($detail->gross_profit) }}</td></tr>
                            <tr><td colspan="3" class="text-end">Modal Terjual</td><td class="text-end">{{ F::rupiah($detail->total_cost) }}</td><td></td></tr>
                            <tr><td colspan="3" class="text-end">Dibayar</td><td class="text-end">{{ F::rupiah($detail->paid_amount) }}</td><td></td></tr>
                            <tr><td colspan="3" class="text-end">Kembalian</td><td class="text-end">{{ F::rupiah($detail->change_amount) }}</td><td></td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
