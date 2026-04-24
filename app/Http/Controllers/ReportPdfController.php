<?php

namespace App\Http\Controllers;

use App\Repositories\FinanceRepository;
use App\Repositories\ReportRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReportPdfController extends Controller
{
    public function sales(Request $request, ReportRepository $reports): Response
    {
        $from = $request->query('dari');
        $to = $request->query('sampai');

        $sales = $reports->salesCollection($from, $to);
        $pdf = Pdf::loadView('pdf.sales-report', [
            'sales' => $sales,
            'totalAmount' => (float) $sales->sum('total'),
            'totalTransactions' => $sales->count(),
            'dateFrom' => $from,
            'dateTo' => $to,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-penjualan-' . now()->format('Ymd_His') . '.pdf');
    }

    public function finance(Request $request, ReportRepository $reports, FinanceRepository $finance): Response
    {
        $from = $request->query('dari');
        $to = $request->query('sampai');
        $type = $request->query('type');

        $rows = $reports->financeCollection($from, $to, $type ?: null);
        $pdf = Pdf::loadView('pdf.finance-report', [
            'rows' => $rows,
            'totalIncome' => $finance->totalIncome($from, $to),
            'totalExpense' => $finance->totalExpense($from, $to),
            'netProfit' => $finance->netProfit($from, $to),
            'dateFrom' => $from,
            'dateTo' => $to,
            'type' => $type,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-keuangan-' . now()->format('Ymd_His') . '.pdf');
    }

    public function profitLoss(Request $request, ReportRepository $reports, FinanceRepository $finance): Response
    {
        $from = $request->query('dari');
        $to = $request->query('sampai');

        $pdf = Pdf::loadView('pdf.profit-loss-report', [
            'incomeRows' => $reports->financeCollection($from, $to, 'income'),
            'expenseRows' => $reports->financeCollection($from, $to, 'expense'),
            'totalIncome' => $finance->totalIncome($from, $to),
            'totalExpense' => $finance->totalExpense($from, $to),
            'netProfit' => $finance->netProfit($from, $to),
            'dateFrom' => $from,
            'dateTo' => $to,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-laba-rugi-' . now()->format('Ymd_His') . '.pdf');
    }

    public function stock(Request $request, ReportRepository $reports): Response
    {
        $all = $reports->stockCollection($request->query('q'), $request->query('status'));
        $pdf = Pdf::loadView('pdf.stock-report', [
            'products' => $all,
            'totalProducts' => $all->count(),
            'totalStock' => (int) $all->sum('stock'),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-stok-' . now()->format('Ymd_His') . '.pdf');
    }
}
