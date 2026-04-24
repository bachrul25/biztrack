<?php

namespace App\Livewire\Reports;

use App\Repositories\FinanceRepository;
use App\Repositories\ReportRepository;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Laporan Laba Rugi')]
class ProfitLossReportComponent extends Component
{
    #[Url(as: 'dari')]
    public string $dateFrom = '';

    #[Url(as: 'sampai')]
    public string $dateTo = '';

    public function mount(): void
    {
        if (! $this->dateFrom) {
            $this->dateFrom = now()->startOfMonth()->toDateString();
        }
        if (! $this->dateTo) {
            $this->dateTo = now()->toDateString();
        }
    }

    public function render(FinanceRepository $finance, ReportRepository $reports): View
    {
        $from = $this->dateFrom ?: null;
        $to = $this->dateTo ?: null;

        $incomeRows = $reports->financeCollection($from, $to, 'income');
        $expenseRows = $reports->financeCollection($from, $to, 'expense');

        return view('livewire.reports.profit-loss-report-component', [
            'incomeRows' => $incomeRows,
            'expenseRows' => $expenseRows,
            'totalIncome' => $finance->totalIncome($from, $to),
            'totalExpense' => $finance->totalExpense($from, $to),
            'netProfit' => $finance->netProfit($from, $to),
        ]);
    }
}
