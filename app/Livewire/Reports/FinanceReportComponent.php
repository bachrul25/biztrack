<?php

namespace App\Livewire\Reports;

use App\Repositories\FinanceRepository;
use App\Repositories\ReportRepository;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Laporan Keuangan')]
class FinanceReportComponent extends Component
{
    use WithPagination;

    #[Url(as: 'dari')]
    public string $dateFrom = '';

    #[Url(as: 'sampai')]
    public string $dateTo = '';

    #[Url(as: 'type')]
    public string $typeFilter = '';

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
    public function updatingTypeFilter(): void { $this->resetPage(); }
    public function updatingSearch(): void { $this->resetPage(); }

    public function render(ReportRepository $reports, FinanceRepository $finance): View
    {
        $from = $this->dateFrom ?: null;
        $to = $this->dateTo ?: null;

        return view('livewire.reports.finance-report-component', [
            'finances' => $reports->financePaginate($from, $to, $this->typeFilter ?: null, $this->search ?: null, 15),
            'totalIncome' => $finance->totalIncome($from, $to),
            'totalExpense' => $finance->totalExpense($from, $to),
            'netProfit' => $finance->netProfit($from, $to),
        ]);
    }
}
