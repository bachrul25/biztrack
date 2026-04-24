<?php

namespace App\Livewire\Reports;

use App\Repositories\ReportRepository;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Laporan Stok')]
class StockReportComponent extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $status = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingStatus(): void { $this->resetPage(); }

    public function render(ReportRepository $reports): View
    {
        $all = $reports->stockCollection($this->search ?: null, $this->status ?: null);
        return view('livewire.reports.stock-report-component', [
            'products' => $reports->stockPaginate($this->search ?: null, $this->status ?: null, 15),
            'totalProducts' => $all->count(),
            'totalStock' => (int) $all->sum('stock'),
            'lowCount' => $all->where('stock', '<', 5)->where('stock', '>', 0)->count(),
            'outCount' => $all->where('stock', '<=', 0)->count(),
        ]);
    }
}
