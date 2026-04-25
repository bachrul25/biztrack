<?php

namespace App\Services;

use App\Repositories\FinanceRepository;
use App\Repositories\ReportRepository;
use App\Repositories\SaleRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ReportService
{
    public function __construct(
        private readonly ReportRepository $report,
        private readonly SaleRepository $saleRepo,
        private readonly FinanceRepository $financeRepo,
    ) {}

    public function salesReport(string $from, string $to): array
    {
        $sales = $this->report->salesBetween($from, $to);
        $totalQty = 0;
        foreach ($sales as $s) {
            foreach ($s->items as $it) {
                $totalQty += (int) $it->quantity;
            }
        }

        return [
            'sales' => $sales,
            'total_transactions' => $sales->count(),
            'total_revenue' => $this->saleRepo->totalRevenue($from, $to),
            'total_cost' => $this->saleRepo->totalCost($from, $to),
            'gross_profit' => $this->saleRepo->grossProfit($from, $to),
            'total_qty' => $totalQty,
        ];
    }

    public function financeReport(string $from, string $to): array
    {
        $records = $this->report->financesBetween($from, $to);
        $totalIncome = $this->financeRepo->totalIncome($from, $to);
        $totalExpense = $this->financeRepo->totalExpense($from, $to);

        return [
            'records' => $records,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net' => $totalIncome - $totalExpense,
        ];
    }

    public function profitLossReport(string $from, string $to): array
    {
        $revenue = $this->saleRepo->totalRevenue($from, $to);
        $cogs = $this->saleRepo->totalCost($from, $to);
        $grossProfit = $this->saleRepo->grossProfit($from, $to);
        $totalExpense = $this->financeRepo->totalExpense($from, $to);
        $netProfit = $grossProfit - $totalExpense;

        return [
            'revenue' => $revenue,
            'cogs' => $cogs,
            'gross_profit' => $grossProfit,
            'total_expense' => $totalExpense,
            'net_profit' => $netProfit,
        ];
    }

    public function stockReport(?string $from = null, ?string $to = null): array
    {
        return [
            'products' => $this->report->productsSnapshot(),
            'low_stock' => $this->report->lowStockProducts(),
            'movements' => ($from && $to) ? $this->report->stockMovementsBetween($from, $to) : collect(),
        ];
    }

    public function pdf(string $view, array $data, string $filename): Response
    {
        $pdf = Pdf::loadView($view, $data)->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }

    public function streamPdf(string $view, array $data, string $filename): Response
    {
        $pdf = Pdf::loadView($view, $data)->setPaper('a4', 'portrait');

        return $pdf->stream($filename);
    }
}
