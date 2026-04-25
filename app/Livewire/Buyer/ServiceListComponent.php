<?php

namespace App\Livewire\Buyer;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceListComponent extends Component
{
    use WithPagination;

    public string $search = '';
    public string $serviceTypeFilter = '';
    public string $categoryFilter = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $services = Service::where('status', 'active')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->serviceTypeFilter, fn($q) => $q->where('service_type', $this->serviceTypeFilter))
            ->when($this->categoryFilter, fn($q) => $q->where('category', $this->categoryFilter))
            ->latest()
            ->paginate(12);

        return view('livewire.buyer.service-list-component', [
            'services' => $services,
        ])->layout('layouts.app', ['title' => 'Services - PT BOBA']);
    }
}
