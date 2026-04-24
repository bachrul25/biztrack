<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Sale;
use App\Repositories\SaleRepository;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;

#[Layout('layouts.app')]
#[Title('Penjualan')]
class SaleComponent extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'dari')]
    public string $dateFrom = '';

    #[Url(as: 'sampai')]
    public string $dateTo = '';

    public bool $showModal = false;

    /** @var array<int, array{product_id: ?int, quantity: int}> */
    public array $items = [];

    public string $saleDate = '';

    public ?int $viewSaleId = null;
    public bool $showView = false;

    public function mount(): void
    {
        $this->saleDate = now()->toDateString();
        $this->addItem();
    }

    protected function rules(): array
    {
        return [
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'saleDate' => ['required', 'date'],
        ];
    }

    protected array $messages = [
        'items.*.product_id.required' => 'Produk wajib dipilih.',
        'items.*.product_id.exists' => 'Produk tidak valid.',
        'items.*.quantity.required' => 'Jumlah wajib diisi.',
        'items.*.quantity.integer' => 'Jumlah harus berupa angka.',
        'items.*.quantity.min' => 'Jumlah minimal 1.',
        'saleDate.required' => 'Tanggal wajib diisi.',
    ];

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

    public function openCreate(): void
    {
        $this->items = [];
        $this->addItem();
        $this->saleDate = now()->toDateString();
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function addItem(): void
    {
        $this->items[] = ['product_id' => null, 'quantity' => 1];
    }

    public function removeItem(int $index): void
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function getSubtotal(int $index): float
    {
        $row = $this->items[$index] ?? null;
        if (! $row || ! $row['product_id']) {
            return 0.0;
        }
        $product = Product::find($row['product_id']);
        if (! $product) {
            return 0.0;
        }
        return (float) $product->price * (int) $row['quantity'];
    }

    public function getTotalProperty(): float
    {
        $total = 0;
        foreach (array_keys($this->items) as $i) {
            $total += $this->getSubtotal($i);
        }
        return $total;
    }

    public function store(SaleRepository $sales): void
    {
        $this->validate();

        try {
            $payload = array_map(fn ($i) => [
                'product_id' => (int) $i['product_id'],
                'quantity' => (int) $i['quantity'],
            ], $this->items);

            $sales->createSale($payload, $this->saleDate);
            $this->dispatch('swal', icon: 'success', title: 'Transaksi berhasil disimpan');
            $this->showModal = false;
            $this->items = [];
            $this->addItem();
        } catch (RuntimeException $e) {
            $this->dispatch('swal', icon: 'error', title: $e->getMessage());
        }
    }

    public function view(int $id): void
    {
        $this->viewSaleId = $id;
        $this->showView = true;
    }

    public function delete(int $id, SaleRepository $sales): void
    {
        $sale = Sale::findOrFail($id);
        $sales->deleteSale($sale);
        $this->dispatch('swal', icon: 'success', title: 'Transaksi dihapus');
    }

    public function render(SaleRepository $sales): View
    {
        $viewSale = $this->viewSaleId
            ? Sale::with(['details.product', 'user'])->find($this->viewSaleId)
            : null;

        return view('livewire.sale-component', [
            'sales' => $sales->paginate($this->search, $this->dateFrom ?: null, $this->dateTo ?: null, 10),
            'products' => Product::where('status', 'active')->orderBy('name')->get(),
            'viewSale' => $viewSale,
        ]);
    }
}
