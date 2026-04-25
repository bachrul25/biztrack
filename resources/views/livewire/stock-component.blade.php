@php use App\Helpers\FormatHelper as F; @endphp
<div>
    <div class="page-header d-flex justify-content-between align-items-end flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Stok Produk</h1>
            <p class="text-muted mb-0">Pergerakan stok masuk, stok keluar, dan rekomendasi restock</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" wire:click="openCreate('in')"><i class="bi bi-box-arrow-in-down"></i> Stok Masuk</button>
            <button class="btn btn-outline-danger" wire:click="openCreate('out')"><i class="bi bi-box-arrow-up"></i> Stok Keluar</button>
        </div>
    </div>

    @if($lowStock->count() > 0)
    <div class="card content-card border-start border-4" style="border-color:#f4a7b9 !important">
        <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-exclamation-triangle text-warning fs-5"></i>
                <h6 class="fw-semibold mb-0">Stok Menipis &amp; Rekomendasi Restock (berdasarkan prediksi Time Series)</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-end">Stok Saat Ini</th>
                            <th class="text-end">Min Stok</th>
                            <th class="text-end">Prediksi Bulan Depan</th>
                            <th class="text-end">Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($lowStock as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td class="text-end"><span class="badge text-bg-{{ $p->stock_badge }}">{{ $p->stock }} {{ $p->unit }}</span></td>
                            <td class="text-end">{{ $p->minimum_stock }}</td>
                            <td class="text-end">
                                {{ isset($stockRecommendations[$p->id]) ? F::number($stockRecommendations[$p->id]['forecast']) : '—' }}
                            </td>
                            <td class="text-end fw-semibold text-success">
                                {{ isset($stockRecommendations[$p->id]) ? F::number($stockRecommendations[$p->id]['recommended_stock']) . ' ' . $p->unit : '—' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <div class="card content-card mt-3">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <select wire:model.live="filterProductId" class="form-select">
                        <option value="">Semua produk</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filterType" class="form-select">
                        <option value="">Semua tipe</option>
                        <option value="in">Masuk</option>
                        <option value="out">Keluar</option>
                    </select>
                </div>
                <div class="col-md-2"><input type="date" wire:model.live="dateFrom" class="form-control"></div>
                <div class="col-md-2"><input type="date" wire:model.live="dateTo" class="form-control"></div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Tipe</th>
                            <th class="text-end">Jumlah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($movements as $m)
                        <tr>
                            <td>{{ F::tanggal($m->movement_date) }}</td>
                            <td>{{ $m->product?->name }}</td>
                            <td>
                                <span class="badge text-bg-{{ $m->type === 'in' ? 'success' : 'danger' }}">
                                    {{ $m->type === 'in' ? 'Masuk' : 'Keluar' }}
                                </span>
                            </td>
                            <td class="text-end">{{ $m->quantity }}</td>
                            <td class="text-muted small">{{ $m->description }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $movements->links() }}</div>
        </div>
    </div>

    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.45);" wire:ignore.self>
        <div class="modal-dialog">
            <form wire:submit.prevent="save" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Catat Stok {{ $movementType === 'in' ? 'Masuk' : 'Keluar' }}
                    </h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Produk <span class="text-danger">*</span></label>
                        <select wire:model="product_id" class="form-select @error('product_id') is-invalid @enderror">
                            <option value="">— Pilih —</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (stok saat ini: {{ $p->stock }})</option>
                            @endforeach
                        </select>
                        @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" min="1" wire:model="quantity" class="form-control @error('quantity') is-invalid @enderror">
                            @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="date" wire:model="movement_date" class="form-control">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Keterangan</label>
                        <textarea wire:model="description" rows="2" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="$set('showModal', false)">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
