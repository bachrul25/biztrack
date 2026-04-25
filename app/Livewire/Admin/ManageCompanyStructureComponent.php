<?php

namespace App\Livewire\Admin;

use App\Models\CompanyStructure;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ManageCompanyStructureComponent extends Component
{
    use WithFileUploads, WithPagination;

    public string $name = '';
    public string $position = '';
    public string $description = '';
    public $photo;
    public int $sort_order = 0;
    public string $status = 'active';
    public ?int $editId = null;
    public bool $showModal = false;

    protected $paginationTheme = 'bootstrap';

    protected function rules(): array
    {
        return [
            'name' => 'required|min:3',
            'position' => 'required',
            'description' => 'nullable',
            'photo' => $this->editId ? 'nullable|image|max:2048' : 'nullable|image|max:2048',
            'sort_order' => 'required|integer',
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
        $structure = CompanyStructure::findOrFail($id);
        $this->editId = $structure->id;
        $this->name = $structure->name;
        $this->position = $structure->position;
        $this->description = $structure->description ?? '';
        $this->sort_order = $structure->sort_order;
        $this->status = $structure->status;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'position' => $this->position,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'status' => $this->status,
        ];

        if ($this->photo) {
            $data['photo'] = $this->photo->store('structures', 'public');
        }

        if ($this->editId) {
            CompanyStructure::findOrFail($this->editId)->update($data);
            session()->flash('success', 'Data berhasil diperbarui.');
        } else {
            CompanyStructure::create($data);
            session()->flash('success', 'Data berhasil ditambahkan.');
        }

        $this->resetFields();
        $this->showModal = false;
    }

    public function toggleStatus(int $id)
    {
        $structure = CompanyStructure::findOrFail($id);
        $structure->update([
            'status' => $structure->status === 'active' ? 'inactive' : 'active',
        ]);
        session()->flash('success', 'Status berhasil diubah.');
    }

    public function delete(int $id)
    {
        CompanyStructure::findOrFail($id)->delete();
        session()->flash('success', 'Data berhasil dihapus.');
    }

    public function resetFields()
    {
        $this->editId = null;
        $this->name = '';
        $this->position = '';
        $this->description = '';
        $this->photo = null;
        $this->sort_order = 0;
        $this->status = 'active';
    }

    public function render()
    {
        return view('livewire.admin.manage-company-structure-component', [
            'structures' => CompanyStructure::orderBy('sort_order')->paginate(10),
        ])->layout('layouts.app', ['title' => 'Manage Company Structure - PT BOBA']);
    }
}
