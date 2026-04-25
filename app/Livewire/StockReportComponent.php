<?php

namespace App\Livewire;

use App\Services\ReportService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class StockReportComponent extends Component
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
        $report = app(ReportService::class)->stockReport($this->dateFrom, $this->dateTo);

        return app(ReportService::class)->pdf(
            'pdf.stock-report',
            array_merge($report, ['dateFrom' => $this->dateFrom, 'dateTo' => $this->dateTo]),
            'laporan-stok-'.$this->dateFrom.'_'.$this->dateTo.'.pdf'
        );
    }

    public function render()
    {
        $report = app(ReportService::class)->stockReport($this->dateFrom, $this->dateTo);

        return view('livewire.stock-report-component', array_merge($report, [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
        ]));
    }
}
