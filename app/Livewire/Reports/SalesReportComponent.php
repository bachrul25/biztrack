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
#[Title('Laporan Penjualan')]
class SalesReportComponent extends Component
{
    use WithPagination;

    #[Url(as: 'dari')]
    public string $dateFrom = '';

    #[Url(as: 'sampai')]
    public string $dateTo = '';

    #[Url(as: 'q')]
    public string $search = '';

    public function mount(): void
    {
        if (! $this->dateFrom) {
            $this->dateFrom = now()->startOfMonth()->toDateString();
        }
        if (! $this->dateTo) {
            $this->dateTo = now()->toDateString();
        }
    }

    public function updatingDateFrom(): void { $this->resetPage(); }
    public function updatingDateTo(): void { $this->resetPage(); }
    public function updatingSearch(): void { $this->resetPage(); }

    public function render(ReportRepository $reports): View
    {
        $paginator = $reports->salesPaginate($this->dateFrom ?: null, $this->dateTo ?: null, $this->search ?: null, 15);
        $all = $reports->salesCollection($this->dateFrom ?: null, $this->dateTo ?: null);
        return view('livewire.reports.sales-report-component', [
            'sales' => $paginator,
            'totalAmount' => (float) $all->sum('total'),
            'totalTransactions' => $all->count(),
        ]);
    }
}
