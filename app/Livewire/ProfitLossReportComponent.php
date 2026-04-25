<?php

namespace App\Livewire;

use App\Services\ReportService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class ProfitLossReportComponent extends Component
{
    #[Url(as: 'from')]
    public ?string $dateFrom = null;

    #[Url(as: 'to')]
    public ?string $dateTo = null;

    public function mount(): void
    {
        $this->dateFrom = $this->dateFrom ?? now()->startOfMonth()->toDateString();
        $this->dateTo = $this->dateTo ?? now()->toDateString();
    }

    public function exportPdf()
    {
        $report = app(ReportService::class)->profitLossReport($this->dateFrom, $this->dateTo);

        return app(ReportService::class)->pdf(
            'pdf.profit-loss-report',
            array_merge($report, ['dateFrom' => $this->dateFrom, 'dateTo' => $this->dateTo]),
            'laporan-laba-rugi-'.$this->dateFrom.'_'.$this->dateTo.'.pdf'
        );
    }

    public function render()
    {
        $report = app(ReportService::class)->profitLossReport($this->dateFrom, $this->dateTo);

        return view('livewire.profit-loss-report-component', array_merge($report, [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
        ]));
    }
}
