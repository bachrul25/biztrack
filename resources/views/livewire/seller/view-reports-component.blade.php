<div>
    @include('partials.seller-navbar')
    <div class="container-fluid py-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-bar-chart me-2"></i>Laporan Seller</h4>

        <div class="row g-2 mb-4 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" wire:model.live="dateFrom" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" wire:model.live="dateTo" class="form-control">
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body py-2 text-center">
                        <small>Total Pendapatan</small>
                        <h5 class="fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-dark text-white"><h6 class="mb-0">Order Produk</h6></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Produk</th>
                                <th>Buyer</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orderItems as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->product->name ?? '-' }}</td>
                                <td>{{ $item->order->buyer->name ?? '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $orderItems->links() }}
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white"><h6 class="mb-0">Booking Layanan</h6></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Layanan</th>
                                <th>Buyer</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->service->name ?? '-' }}</td>
                                <td>{{ $booking->buyer->name ?? '-' }}</td>
                                <td><span class="badge {{ match($booking->booking_status) { 'completed' => 'bg-success', 'processing' => 'bg-info', 'pending' => 'bg-warning', 'cancelled' => 'bg-danger' } }}">{{ $booking->booking_status }}</span></td>
                                <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
