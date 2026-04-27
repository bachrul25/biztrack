<?php

namespace App\Livewire;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Services\CategoryService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class CategoryComponent extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public ?string $description = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100', 'unique:categories,name,'.($this->editingId ?? 'NULL').',id'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['editingId', 'name', 'description']);
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $cat = Category::findOrFail($id);
        $this->editingId = $cat->id;
        $this->name = $cat->name;
        $this->description = $cat->description;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        /** @var CategoryService $svc */
        $svc = app(CategoryService::class);

        if ($this->editingId) {
            $svc->update(Category::findOrFail($this->editingId), $data);
            $this->dispatch('swal', icon: 'success', title: 'Kategori diperbarui');
        } else {
            $svc->create($data);
            $this->dispatch('swal', icon: 'success', title: 'Kategori ditambahkan');
        }
        $this->showModal = false;
        $this->reset(['editingId', 'name', 'description']);
    }

    public function delete(int $id): void
    {
        try {
            app(CategoryService::class)->delete(Category::findOrFail($id));
            $this->dispatch('swal', icon: 'success', title: 'Kategori dihapus');
        } catch (\DomainException $e) {
            $this->dispatch('swal', icon: 'error', title: 'Gagal menghapus', text: $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.category-component', [
            'categories' => app(CategoryRepository::class)->paginate($this->search ?: null, 10),
        ]);
    }
}
