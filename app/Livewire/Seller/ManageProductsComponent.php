<?php

namespace App\Livewire\Seller;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ManageProductsComponent extends Component
{
    use WithFileUploads, WithPagination;

    public string $brand = 'tsoecha.co';
    public string $gender_category = 'pria';
    public string $name = '';
    public string $description = '';
    public $price = '';
    public $stock = '';
    public string $category = '';
    public $image;
    public string $status = 'active';
    public ?int $editId = null;
    public bool $showModal = false;

    protected $paginationTheme = 'bootstrap';

    protected function rules(): array
    {
        return [
            'brand' => 'required|in:tsoecha.co,sokyuut',
            'gender_category' => 'required|in:pria,wanita',
            'name' => 'required|min:3',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required',
            'image' => $this->editId ? 'nullable|image|max:2048' : 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function updatedBrand($value)
    {
        $this->gender_category = $value === 'tsoecha.co' ? 'pria' : 'wanita';
    }

    public function openModal()
    {
        $this->resetFields();
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        $this->editId = $product->id;
        $this->brand = $product->brand;
        $this->gender_category = $product->gender_category;
        $this->name = $product->name;
        $this->description = $product->description ?? '';
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->category = $product->category ?? '';
        $this->status = $product->status;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'seller_id' => Auth::id(),
            'brand' => $this->brand,
            'gender_category' => $this->gender_category,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'category' => $this->category,
            'status' => $this->status,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('products', 'public');
        }

        if ($this->editId) {
            Product::where('seller_id', Auth::id())->findOrFail($this->editId)->update($data);
            session()->flash('success', 'Produk berhasil diperbarui.');
        } else {
            Product::create($data);
            session()->flash('success', 'Produk berhasil ditambahkan.');
        }

        $this->resetFields();
        $this->showModal = false;
    }

    public function toggleStatus(int $id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        $product->update(['status' => $product->status === 'active' ? 'inactive' : 'active']);
        session()->flash('success', 'Status produk diubah.');
    }

    public function delete(int $id)
    {
        Product::where('seller_id', Auth::id())->findOrFail($id)->delete();
        session()->flash('success', 'Produk berhasil dihapus.');
    }

    public function resetFields()
    {
        $this->editId = null;
        $this->brand = 'tsoecha.co';
        $this->gender_category = 'pria';
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->stock = '';
        $this->category = '';
        $this->image = null;
        $this->status = 'active';
    }

    public function render()
    {
        $products = Product::where('seller_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('livewire.seller.manage-products-component', [
            'products' => $products,
        ])->layout('layouts.app', ['title' => 'Manage Products - PT BOBA']);
    }
}
