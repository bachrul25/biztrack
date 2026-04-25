<div>
    @include('partials.admin-navbar')
    <div class="container-fluid py-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-bar-chart me-2"></i>Laporan</h4>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Jenis Laporan</label>
                        <select wire:model.live="reportType" class="form-select">
                            <option value="orders">Order Produk</option>
                            <option value="bookings">Booking Layanan</option>
                            <option value="payments">Pembayaran</option>
                        </select>
                    </div>
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
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @if($reportType === 'orders')
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Buyer</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status Order</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->buyer->name ?? '-' }}</td>
                                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td><span class="badge {{ match($order->payment_status) { 'success' => 'bg-success', 'pending' => 'bg-warning', 'failed' => 'bg-danger' } }}">{{ $order->payment_status }}</span></td>
                                <td><span class="badge {{ match($order->order_status) { 'completed' => 'bg-success', 'processing' => 'bg-info', 'pending' => 'bg-warning', 'cancelled' => 'bg-danger' } }}">{{ $order->order_status }}</span></td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $orders->links() }}
                    @endif
                </div>
                @endif

                @if($reportType === 'bookings')
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Buyer</th>
                                <th>Layanan</th>
                                <th>Seller</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>{{ $booking->buyer->name ?? '-' }}</td>
                                <td>{{ $booking->service->name ?? '-' }}</td>
                                <td>{{ $booking->seller->name ?? '-' }}</td>
                                <td><span class="badge {{ match($booking->payment_status) { 'success' => 'bg-success', 'pending' => 'bg-warning', 'failed' => 'bg-danger' } }}">{{ $booking->payment_status }}</span></td>
                                <td><span class="badge {{ match($booking->booking_status) { 'completed' => 'bg-success', 'processing' => 'bg-info', 'pending' => 'bg-warning', 'cancelled' => 'bg-danger' } }}">{{ $booking->booking_status }}</span></td>
                                <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $bookings->links() }}
                    @endif
                </div>
                @endif

                @if($reportType === 'payments')
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->id }}</td>
                                <td>{{ $payment->user->name ?? '-' }}</td>
                                <td>{{ $payment->payment_method }}</td>
                                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td><span class="badge {{ match($payment->status) { 'success' => 'bg-success', 'pending' => 'bg-warning', 'failed' => 'bg-danger' } }}">{{ $payment->status }}</span></td>
                                <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if($payments instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $payments->links() }}
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
