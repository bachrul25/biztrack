<?php

namespace App\Livewire\Admin;

use App\Models\SellerProfile;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ManageSellersComponent extends Component
{
    use WithPagination;

    public string $search = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function approve(int $profileId)
    {
        SellerProfile::findOrFail($profileId)->update(['status' => 'approved']);
        session()->flash('success', 'Seller berhasil di-approve.');
    }

    public function reject(int $profileId)
    {
        SellerProfile::findOrFail($profileId)->update(['status' => 'rejected']);
        session()->flash('success', 'Seller berhasil di-reject.');
    }

    public function deleteSeller(int $userId)
    {
        User::findOrFail($userId)->delete();
        session()->flash('success', 'Seller berhasil dihapus.');
    }

    public function render()
    {
        $sellers = User::where('role', 'seller')
            ->with('sellerProfile')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manage-sellers-component', [
            'sellers' => $sellers,
        ])->layout('layouts.app', ['title' => 'Manage Sellers - PT BOBA']);
    }
}
