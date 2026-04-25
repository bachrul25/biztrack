<?php

namespace App\Livewire;

use App\Services\ReportService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class SalesReportComponent extends Component
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
        $report = app(ReportService::class)->salesReport($this->dateFrom, $this->dateTo);

        return app(ReportService::class)->pdf(
            'pdf.sales-report',
            array_merge($report, ['dateFrom' => $this->dateFrom, 'dateTo' => $this->dateTo]),
            'laporan-penjualan-'.$this->dateFrom.'_'.$this->dateTo.'.pdf'
        );
    }

    public function render()
    {
        $report = app(ReportService::class)->salesReport($this->dateFrom, $this->dateTo);

        return view('livewire.sales-report-component', array_merge($report, [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
        ]));
    }
}
