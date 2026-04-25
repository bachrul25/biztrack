<?php

namespace App\Livewire;

use App\Models\Product;
use App\Repositories\StockRepository;
use App\Services\PredictionService;
use App\Services\StockService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class StockComponent extends Component
{
    use WithPagination;

    public ?int $filterProductId = null;

    public ?string $filterType = null;

    public ?string $dateFrom = null;

    public ?string $dateTo = null;

    public bool $showModal = false;

    public string $movementType = 'in';

    public ?int $product_id = null;

    public int $quantity = 0;

    public ?string $description = null;

    public ?string $movement_date = null;

    public function mount(): void
    {
        $this->movement_date = now()->toDateString();
    }

    protected function rules(): array
    {
        return [
            'movementType' => ['required', 'in:in,out'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'movement_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function openCreate(string $type = 'in'): void
    {
        $this->reset(['product_id', 'quantity', 'description']);
        $this->movementType = $type;
        $this->movement_date = now()->toDateString();
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        /** @var StockService $svc */
        $svc = app(StockService::class);
        try {
            $product = Product::findOrFail($data['product_id']);
            if ($data['movementType'] === 'in') {
                $svc->recordIn($product, (int) $data['quantity'], $data['description'] ?? null, $data['movement_date']);
            } else {
                $svc->recordOut($product, (int) $data['quantity'], $data['description'] ?? null, $data['movement_date']);
            }
            $this->dispatch('swal', icon: 'success', title: 'Pergerakan stok dicatat');
            $this->showModal = false;
        } catch (\DomainException $e) {
            $this->dispatch('swal', icon: 'error', title: 'Gagal menyimpan', text: $e->getMessage());
        }
    }

    public function render()
    {
        $repo = app(StockRepository::class);
        $products = Product::orderBy('name')->get();
        $movements = $repo->movements($this->filterProductId, $this->filterType, $this->dateFrom, $this->dateTo, 15);
        $lowStock = Product::whereColumn('stock', '<=', 'minimum_stock')->orderBy('stock')->get();

        // Restock recommendations using the last 6 monthly periods
        $predictionSvc = app(PredictionService::class);
        $recs = [];
        foreach ($lowStock as $p) {
            $hist = $predictionSvc->historicalSeries('monthly', $p->id);
            if (count($hist) < 2) {
                continue;
            }
            $forecast = $predictionSvc->forecast('linear_trend', $hist, 1, [], 'monthly');
            $recs[$p->id] = $predictionSvc->stockRecommendation((float) ($forecast['forecast'][0] ?? 0), 0.15);
        }

        return view('livewire.stock-component', [
            'movements' => $movements,
            'products' => $products,
            'lowStock' => $lowStock,
            'stockRecommendations' => $recs,
        ]);
    }
}
