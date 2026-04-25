<div>
    @include('partials.seller-navbar')
    <div class="container py-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-person me-2"></i>Seller Profile</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($profile)
        <div class="mb-3">
            <span class="badge fs-6 {{ match($profile->status) { 'approved' => 'bg-success', 'pending' => 'bg-warning', 'rejected' => 'bg-danger' } }}">Status: {{ ucfirst($profile->status) }}</span>
            <span class="badge fs-6 {{ $profile->is_completed ? 'bg-success' : 'bg-warning' }} ms-1">{{ $profile->is_completed ? 'Profil Lengkap' : 'Profil Belum Lengkap' }}</span>
        </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form wire:submit="save">
                    <div class="mb-3">
                        <label class="form-label">Nama Toko</label>
                        <input type="text" wire:model="shop_name" class="form-control @error('shop_name') is-invalid @enderror">
                        @error('shop_name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Toko</label>
                        <textarea wire:model="shop_description" class="form-control @error('shop_description') is-invalid @enderror" rows="4"></textarea>
                        @error('shop_description') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Toko</label>
                        <textarea wire:model="shop_address" class="form-control @error('shop_address') is-invalid @enderror" rows="3"></textarea>
                        @error('shop_address') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Simpan Profil</button>
                </form>
            </div>
        </div>
    </div>
</div>
