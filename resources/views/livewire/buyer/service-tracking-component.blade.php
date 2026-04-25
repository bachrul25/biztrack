<div>
    @include('partials.buyer-navbar')
    <div class="container py-4">
        <a href="/buyer/dashboard" class="btn btn-secondary btn-sm mb-3"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>

        <h4 class="fw-bold mb-4"><i class="bi bi-truck me-2"></i>Service Tracking</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-3">Detail Layanan</h5>
                        <table class="table table-borderless">
                            <tr><td class="fw-bold">Layanan:</td><td>{{ $booking->service->name ?? '-' }}</td></tr>
                            <tr><td class="fw-bold">Jenis:</td><td>{{ str_replace('_', ' ', ucwords($booking->service->service_type ?? '', '_')) }}</td></tr>
                            <tr><td class="fw-bold">Seller:</td><td>{{ $booking->seller->name ?? '-' }}</td></tr>
                            <tr><td class="fw-bold">Requirement:</td><td>{{ $booking->requirement }}</td></tr>
                            <tr><td class="fw-bold">Payment:</td><td>{{ $booking->payment_method }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-3">Status</h5>
                        <div class="mb-3">
                            <span class="badge fs-6 {{ match($booking->booking_status) {
                                'completed' => 'bg-success',
                                'processing' => 'bg-info',
                                'pending' => 'bg-warning',
                                'cancelled' => 'bg-danger',
                            } }}">{{ ucfirst($booking->booking_status) }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="badge fs-6 {{ match($booking->payment_status) {
                                'success' => 'bg-success',
                                'pending' => 'bg-warning',
                                'failed' => 'bg-danger',
                            } }}">Payment: {{ ucfirst($booking->payment_status) }}</span>
                        </div>
                        @if($booking->booking_status === 'processing')
                        <button wire:click="markComplete" wire:confirm="Tandai layanan ini selesai?" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle me-1"></i> Service Complete
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
