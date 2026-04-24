@php
    $fmtIdr = fn ($v) => 'Rp ' . number_format((float) $v, 0, ',', '.');
@endphp
<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h3 class="mb-1 fw-bold">Transaksi Penjualan</h3>
            <p class="text-muted mb-0">Catat transaksi penjualan kue. Stok & keuangan diperbarui otomatis.</p>
        </div>
        <button wire:click="openCreate" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Transaksi Baru
        </button>
    </div>

    <div class="card content-card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input wire:model.live.debounce.400ms="search" type="text"
                               class="form-control" placeholder="Cari nomor invoice...">
                    </div>
                </div>
                <div class="col-md-3">
                    <input wire:model.live="dateFrom" type="date" class="form-control" placeholder="Dari">
                </div>
                <div class="col-md-3">
                    <input wire:model.live="dateTo" type="date" class="form-control" placeholder="Sampai">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Invoice</th>
                            <th>Kasir</th>
                            <th class="text-end">Jumlah Item</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($sales as $s)
                        <tr wire:key="sale-{{ $s->id }}">
                            <td>{{ $s->date?->format('d M Y') }}</td>
                            <td class="fw-semibold">{{ $s->invoice_number }}</td>
                            <td>{{ $s->user?->name ?? '—' }}</td>
                            <td class="text-end">{{ $s->details->sum('quantity') }}</td>
                            <td class="text-end fw-semibold">{{ $fmtIdr($s->total) }}</td>
                            <td class="text-end">
                                <button wire:click="view({{ $s->id }})" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDelete(() => @this.delete({{ $s->id }}), 'Stok akan dikembalikan & pemasukan dibatalkan.')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada transaksi penjualan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $sales->links() }}</div>
        </div>
    </div>

    {{-- Create modal --}}
    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.45)">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <form wire:submit.prevent="store">
                        <div class="modal-header">
                            <h5 class="modal-title">Transaksi Penjualan Baru</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-2 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Transaksi</label>
                                    <input wire:model="saleDate" type="date" class="form-control @error('saleDate') is-invalid @enderror">
                                    @error('saleDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width: 40%">Produk</th>
                                            <th>Harga</th>
                                            <th style="width: 140px">Jumlah</th>
                                            <th>Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($items as $i => $row)
                                        @php
                                            $sel = collect($products)->firstWhere('id', $row['product_id']);
                                        @endphp
                                        <tr wire:key="item-{{ $i }}">
                                            <td>
                                                <select wire:model.live="items.{{ $i }}.product_id"
                                                        class="form-select @error("items.$i.product_id") is-invalid @enderror">
                                                    <option value="">Pilih produk...</option>
                                                    @foreach ($products as $prod)
                                                        <option value="{{ $prod->id }}">
                                                            {{ $prod->name }} (stok: {{ $prod->stock }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error("items.$i.product_id") <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </td>
                                            <td>{{ $sel ? $fmtIdr($sel->price) : '—' }}</td>
                                            <td>
                                                <input wire:model.live="items.{{ $i }}.quantity" type="number" min="1"
                                                       class="form-control @error("items.$i.quantity") is-invalid @enderror">
                                                @error("items.$i.quantity") <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </td>
                                            <td class="fw-semibold">{{ $fmtIdr($this->getSubtotal($i)) }}</td>
                                            <td>
                                                <button type="button" wire:click="removeItem({{ $i }})" class="btn btn-sm btn-outline-danger"
                                                        @disabled(count($items) <= 1)>
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <button type="button" wire:click="addItem" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus-lg"></i> Tambah Produk
                            </button>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <h5 class="mb-0">Total: <span class="text-primary">{{ $fmtIdr($this->total) }}</span></h5>
                            <div>
                                <button type="button" class="btn btn-light" wire:click="$set('showModal', false)">Batal</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check2-circle"></i> Proses Transaksi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- View modal --}}
    @if ($showView && $viewSale)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.45)">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Transaksi #{{ $viewSale->invoice_number }}</h5>
                        <button type="button" class="btn-close" wire:click="$set('showView', false)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6"><small class="text-muted">Tanggal</small><div class="fw-semibold">{{ $viewSale->date?->format('d M Y') }}</div></div>
                            <div class="col-md-6"><small class="text-muted">Kasir</small><div class="fw-semibold">{{ $viewSale->user?->name }}</div></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead><tr><th>Produk</th><th class="text-end">Qty</th><th class="text-end">Harga</th><th class="text-end">Subtotal</th></tr></thead>
                                <tbody>
                                @foreach ($viewSale->details as $d)
                                    <tr>
                                        <td>{{ $d->product?->name ?? '—' }}</td>
                                        <td class="text-end">{{ $d->quantity }}</td>
                                        <td class="text-end">{{ $fmtIdr($d->price) }}</td>
                                        <td class="text-end">{{ $fmtIdr($d->subtotal) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold"><td colspan="3" class="text-end">Total</td><td class="text-end">{{ $fmtIdr($viewSale->total) }}</td></tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
