<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Sale;
use App\Repositories\ProductRepository;
use App\Repositories\SaleRepository;
use App\Services\SaleService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class SaleComponent extends Component
{
    use WithPagination;

    public string $mode = 'list'; // list | create | detail

    public ?int $detailId = null;

    // filters
    public string $search = '';

    public ?string $dateFrom = null;

    public ?string $dateTo = null;

    // POS state
    public array $cart = [];

    public string $productSearch = '';

    public string $paymentMethod = 'cash';

    public float $paidAmount = 0;

    public ?string $saleDate = null;

    public function mount(): void
    {
        $this->saleDate = now()->toDateString();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function startCreate(): void
    {
        $this->cart = [];
        $this->productSearch = '';
        $this->paymentMethod = 'cash';
        $this->paidAmount = 0;
        $this->saleDate = now()->toDateString();
        $this->mode = 'create';
    }

    public function openDetail(int $id): void
    {
        $this->detailId = $id;
        $this->mode = 'detail';
    }

    public function backToList(): void
    {
        $this->mode = 'list';
        $this->detailId = null;
    }

    public function addToCart(int $productId): void
    {
        $p = Product::find($productId);
        if (! $p || $p->status !== 'active') {
            return;
        }
        foreach ($this->cart as $i => $row) {
            if ($row['product_id'] === $productId) {
                if ($this->cart[$i]['quantity'] + 1 > $p->stock) {
                    $this->dispatch('swal', icon: 'warning', title: 'Stok tidak cukup', text: "Stok {$p->name} hanya {$p->stock}.");

                    return;
                }
                $this->cart[$i]['quantity']++;
                $this->cart[$i]['subtotal'] = $this->cart[$i]['price'] * $this->cart[$i]['quantity'];

                return;
            }
        }
        if ($p->stock <= 0) {
            $this->dispatch('swal', icon: 'warning', title: 'Stok habis', text: "Produk {$p->name} stoknya habis.");

            return;
        }
        $this->cart[] = [
            'product_id' => $p->id,
            'name' => $p->name,
            'price' => (float) $p->selling_price,
            'cost_price' => (float) $p->cost_price,
            'quantity' => 1,
            'subtotal' => (float) $p->selling_price,
            'stock' => (int) $p->stock,
            'unit' => $p->unit,
        ];
    }

    public function incrementQty(int $index): void
    {
        if (! isset($this->cart[$index])) {
            return;
        }
        $row = $this->cart[$index];
        if ($row['stock'] < $row['quantity'] + 1) {
            $this->dispatch('swal', icon: 'warning', title: 'Stok tidak cukup');

            return;
        }
        $this->cart[$index]['quantity']++;
        $this->cart[$index]['subtotal'] = $this->cart[$index]['price'] * $this->cart[$index]['quantity'];
    }

    public function decrementQty(int $index): void
    {
        if (! isset($this->cart[$index])) {
            return;
        }
        if ($this->cart[$index]['quantity'] <= 1) {
            $this->removeFromCart($index);

            return;
        }
        $this->cart[$index]['quantity']--;
        $this->cart[$index]['subtotal'] = $this->cart[$index]['price'] * $this->cart[$index]['quantity'];
    }

    public function removeFromCart(int $index): void
    {
        array_splice($this->cart, $index, 1);
    }

    public function getTotalProperty(): float
    {
        return array_sum(array_map(fn ($r) => (float) $r['subtotal'], $this->cart));
    }

    public function getChangeProperty(): float
    {
        return max(0, (float) $this->paidAmount - $this->total);
    }

    public function checkout(): void
    {
        if (empty($this->cart)) {
            $this->dispatch('swal', icon: 'warning', title: 'Keranjang kosong');

            return;
        }
        $this->validate([
            'paymentMethod' => ['required', 'in:cash,transfer,qris'],
            'paidAmount' => ['required', 'numeric', 'min:0'],
            'saleDate' => ['required', 'date'],
        ]);
        try {
            $sale = app(SaleService::class)->process(
                array_map(fn ($r) => ['product_id' => $r['product_id'], 'quantity' => (int) $r['quantity']], $this->cart),
                [
                    'payment_method' => $this->paymentMethod,
                    'paid_amount' => $this->paymentMethod === 'cash' ? $this->paidAmount : $this->total,
                    'sale_date' => $this->saleDate,
                    'user_id' => auth()->id(),
                ]
            );
            $this->dispatch('swal', icon: 'success', title: 'Transaksi berhasil', text: 'Invoice '.$sale->invoice_number);
            $this->detailId = $sale->id;
            $this->cart = [];
            $this->mode = 'detail';
        } catch (\DomainException $e) {
            $this->dispatch('swal', icon: 'error', title: 'Gagal', text: $e->getMessage());
        }
    }

    public function delete(int $id): void
    {
        try {
            $sale = Sale::findOrFail($id);
            app(SaleService::class)->delete($sale);
            $this->dispatch('swal', icon: 'success', title: 'Transaksi dihapus');
        } catch (\Throwable $e) {
            $this->dispatch('swal', icon: 'error', title: 'Gagal', text: $e->getMessage());
        }
    }

    public function render()
    {
        $saleRepo = app(SaleRepository::class);
        $productRepo = app(ProductRepository::class);
        $sales = $saleRepo->paginate($this->search ?: null, $this->dateFrom, $this->dateTo, 10);
        $products = $this->mode === 'create'
            ? ($this->productSearch !== ''
                ? $productRepo->searchActive($this->productSearch)
                : $productRepo->allActive())
            : collect();
        $detail = $this->mode === 'detail' && $this->detailId ? $saleRepo->find($this->detailId) : null;

        return view('livewire.sale-component', [
            'sales' => $sales,
            'products' => $products,
            'detail' => $detail,
        ]);
    }
}
