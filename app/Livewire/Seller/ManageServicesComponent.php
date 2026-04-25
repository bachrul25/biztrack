<?php

namespace App\Livewire\Seller;

use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ManageServicesComponent extends Component
{
    use WithFileUploads, WithPagination;

    public string $service_type = 'pengambilan_sampah';
    public string $name = '';
    public string $description = '';
    public $price = '';
    public string $category = '';
    public $image;
    public string $status = 'active';
    public ?int $editId = null;
    public bool $showModal = false;

    protected $paginationTheme = 'bootstrap';

    protected function rules(): array
    {
        return [
            'service_type' => 'required|in:pengambilan_sampah,pengelolaan_sampah,pengolahan_sampah_organik,bahan_bakar_kendaraan',
            'name' => 'required|min:3',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable',
            'image' => $this->editId ? 'nullable|image|max:2048' : 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function openModal()
    {
        $this->resetFields();
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        $service = Service::where('seller_id', Auth::id())->findOrFail($id);
        $this->editId = $service->id;
        $this->service_type = $service->service_type;
        $this->name = $service->name;
        $this->description = $service->description ?? '';
        $this->price = $service->price;
        $this->category = $service->category ?? '';
        $this->status = $service->status;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'seller_id' => Auth::id(),
            'brand' => 'tos2bro',
            'service_type' => $this->service_type,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category' => $this->category,
            'status' => $this->status,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('services', 'public');
        }

        if ($this->editId) {
            Service::where('seller_id', Auth::id())->findOrFail($this->editId)->update($data);
            session()->flash('success', 'Layanan berhasil diperbarui.');
        } else {
            Service::create($data);
            session()->flash('success', 'Layanan berhasil ditambahkan.');
        }

        $this->resetFields();
        $this->showModal = false;
    }

    public function toggleStatus(int $id)
    {
        $service = Service::where('seller_id', Auth::id())->findOrFail($id);
        $service->update(['status' => $service->status === 'active' ? 'inactive' : 'active']);
        session()->flash('success', 'Status layanan diubah.');
    }

    public function delete(int $id)
    {
        Service::where('seller_id', Auth::id())->findOrFail($id)->delete();
        session()->flash('success', 'Layanan berhasil dihapus.');
    }

    public function resetFields()
    {
        $this->editId = null;
        $this->service_type = 'pengambilan_sampah';
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->category = '';
        $this->image = null;
        $this->status = 'active';
    }

    public function render()
    {
        $services = Service::where('seller_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('livewire.seller.manage-services-component', [
            'services' => $services,
        ])->layout('layouts.app', ['title' => 'Manage Services - PT BOBA']);
    }
}
