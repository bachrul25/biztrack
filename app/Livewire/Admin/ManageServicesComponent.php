<?php

namespace App\Livewire\Admin;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class ManageServicesComponent extends Component
{
    use WithPagination;

    public string $search = '';
    public string $serviceTypeFilter = '';
    public string $statusFilter = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus(int $id)
    {
        $service = Service::findOrFail($id);
        $service->update([
            'status' => $service->status === 'active' ? 'inactive' : 'active',
        ]);
        session()->flash('success', 'Status layanan berhasil diubah.');
    }

    public function deleteService(int $id)
    {
        Service::findOrFail($id)->delete();
        session()->flash('success', 'Layanan berhasil dihapus.');
    }

    public function render()
    {
        $services = Service::with('seller')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->serviceTypeFilter, fn($q) => $q->where('service_type', $this->serviceTypeFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manage-services-component', [
            'services' => $services,
        ])->layout('layouts.app', ['title' => 'Manage Services - PT BOBA']);
    }
}
