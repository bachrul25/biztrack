<div>
    @include('partials.buyer-navbar')
    <div class="container py-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-gear me-2"></i>Layanan tos2bro - Green Technology</h4>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <div class="row g-2 mb-4">
            <div class="col-md-4">
                <input type="text" wire:model.live="search" class="form-control" placeholder="Cari layanan...">
            </div>
            <div class="col-md-3">
                <select wire:model.live="serviceTypeFilter" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="pengambilan_sampah">Pengambilan Sampah</option>
                    <option value="pengelolaan_sampah">Pengelolaan Sampah</option>
                    <option value="pengolahan_sampah_organik">Pengolahan Sampah Organik</option>
                    <option value="bahan_bakar_kendaraan">Bahan Bakar Kendaraan</option>
                </select>
            </div>
        </div>

        <div class="row g-3">
            @forelse($services as $service)
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm card-hover h-100">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" class="card-img-top" style="height:200px;object-fit:cover;">
                    @else
                        <div class="bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="height:200px;">
                            <i class="bi bi-recycle text-success" style="font-size:3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <span class="badge bg-success mb-2">{{ str_replace('_', ' ', ucwords($service->service_type, '_')) }}</span>
                        <h5 class="fw-bold">{{ $service->name }}</h5>
                        <p class="text-muted small">{{ Str::limit($service->description, 100) }}</p>
                        <h5 class="text-success fw-bold">Rp {{ number_format($service->price, 0, ',', '.') }}</h5>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                        <a href="/buyer/services/{{ $service->id }}/booking" class="btn btn-success w-100">
                            <i class="bi bi-calendar-check me-1"></i> Booking
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-5">
                <i class="bi bi-gear display-4"></i>
                <p class="mt-2">Tidak ada layanan ditemukan.</p>
            </div>
            @endforelse
        </div>
        <div class="mt-4">{{ $services->links() }}</div>
    </div>
</div>
