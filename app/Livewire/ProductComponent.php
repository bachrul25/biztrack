<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ProductComponent extends Component
{
    use WithFileUploads;
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'cat')]
    public ?int $filterCategory = null;

    public bool $showModal = false;

    public bool $showDetail = false;

    public ?int $editingId = null;

    public ?int $detailId = null;

    // form fields
    public ?int $category_id = null;

    public string $name = '';

    public string $code = '';

    public ?string $description = null;

    public $imageUpload = null;

    public ?string $existingImage = null;

    public float $cost_price = 0;

    public float $selling_price = 0;

    public int $stock = 0;

    public int $minimum_stock = 5;

    public string $unit = 'pcs';

    public string $status = 'active';

    protected function rules(): array
    {
        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'code' => ['nullable', 'string', 'max:50', 'unique:products,code,'.($this->editingId ?? 'NULL').',id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'imageUpload' => ['nullable', 'image', 'max:2048'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:20'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterCategory(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['editingId', 'category_id', 'name', 'code', 'description', 'imageUpload', 'existingImage', 'cost_price', 'selling_price', 'stock', 'minimum_stock']);
        $this->unit = 'pcs';
        $this->status = 'active';
        $this->code = Product::generateCode();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $p = Product::findOrFail($id);
        $this->editingId = $p->id;
        $this->category_id = $p->category_id;
        $this->name = $p->name;
        $this->code = $p->code;
        $this->description = $p->description;
        $this->cost_price = (float) $p->cost_price;
        $this->selling_price = (float) $p->selling_price;
        $this->stock = (int) $p->stock;
        $this->minimum_stock = (int) $p->minimum_stock;
        $this->unit = $p->unit;
        $this->status = $p->status;
        $this->existingImage = $p->image;
        $this->imageUpload = null;
        $this->showModal = true;
    }

    public function openDetail(int $id): void
    {
        $this->detailId = $id;
        $this->showDetail = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        /** @var ProductService $svc */
        $svc = app(ProductService::class);
        $image = $this->imageUpload ?: null;
        unset($data['imageUpload']);

        if ($this->editingId) {
            $svc->update(Product::findOrFail($this->editingId), $data, $image);
            $this->dispatch('swal', icon: 'success', title: 'Produk diperbarui');
        } else {
            $svc->create($data, $image);
            $this->dispatch('swal', icon: 'success', title: 'Produk ditambahkan');
        }
        $this->showModal = false;
    }

    public function delete(int $id): void
    {
        try {
            app(ProductService::class)->delete(Product::findOrFail($id));
            $this->dispatch('swal', icon: 'success', title: 'Produk dihapus');
        } catch (\Throwable $e) {
            $this->dispatch('swal', icon: 'error', title: 'Gagal menghapus', text: $e->getMessage());
        }
    }

    public function render()
    {
        $products = app(ProductRepository::class)->paginate($this->search ?: null, $this->filterCategory, 10);
        $detail = $this->detailId ? Product::with('category')->find($this->detailId) : null;

        return view('livewire.product-component', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'detail' => $detail,
        ]);
    }
}
