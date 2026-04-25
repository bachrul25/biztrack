<?php

namespace App\Livewire;

use App\Repositories\FinanceRepository;
use App\Repositories\PredictionRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SaleRepository;
use App\Services\PredictionService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DashboardComponent extends Component
{
    public function render()
    {
        $saleRepo = app(SaleRepository::class);
        $financeRepo = app(FinanceRepository::class);
        $productRepo = app(ProductRepository::class);
        $predictionSvc = app(PredictionService::class);

        $totalProducts = $productRepo->countActive();
        $totalStock = $productRepo->totalStock();
        $totalTransactions = $saleRepo->countSales();
        $totalRevenue = $saleRepo->totalRevenue();
        $totalCost = $saleRepo->totalCost();
        $grossProfit = $saleRepo->grossProfit();
        $totalExpense = $financeRepo->totalExpense();
        $netProfit = $grossProfit - $totalExpense;

        $daily = $saleRepo->dailySeries(now()->subDays(29)->toDateString(), now()->toDateString());
        $monthly = $saleRepo->monthlySeries(12);
        $incomeExpense = $financeRepo->monthlyIncomeExpense(6);

        // Simple prediction for the dashboard: 3-month forecast using linear trend
        $historical = $predictionSvc->historicalSeries('monthly');
        $predictionChart = null;
        $stockRecommendation = null;
        if (count($historical) >= 2) {
            $forecast = $predictionSvc->forecast('linear_trend', $historical, 3, [], 'monthly');
            $predictionChart = [
                'labels' => array_merge(array_column($historical, 'period'), $forecast['forecast_periods']),
                'actual' => array_merge(array_column($historical, 'value'), array_fill(0, 3, null)),
                'predicted' => array_merge(array_fill(0, count($historical), null), $forecast['forecast']),
            ];
            $nextMonthForecast = $forecast['forecast'][0] ?? 0;
            $stockRecommendation = $predictionSvc->stockRecommendation((float) $nextMonthForecast, 0.15);
        }

        return view('livewire.dashboard-component', [
            'totalProducts' => $totalProducts,
            'totalStock' => $totalStock,
            'totalTransactions' => $totalTransactions,
            'totalRevenue' => $totalRevenue,
            'totalCost' => $totalCost,
            'grossProfit' => $grossProfit,
            'totalExpense' => $totalExpense,
            'netProfit' => $netProfit,
            'todaySales' => $saleRepo->totalRevenue(now()->toDateString(), now()->toDateString()),
            'todayTxnCount' => $saleRepo->countSales(now()->toDateString(), now()->toDateString()),
            'topProducts' => $saleRepo->topProducts(5, now()->subDays(30)->toDateString(), now()->toDateString()),
            'lowStock' => $productRepo->lowStock(8),
            'dailySeries' => $daily,
            'monthlySeries' => $monthly,
            'incomeExpenseSeries' => $incomeExpense,
            'predictionChart' => $predictionChart,
            'stockRecommendation' => $stockRecommendation,
            'recentPredictions' => app(PredictionRepository::class)->latest(5),
        ]);
    }
}
