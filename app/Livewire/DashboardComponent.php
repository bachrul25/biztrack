<?php

namespace App\Livewire;

use App\Repositories\ProductRepository;
use App\Repositories\ReportRepository;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class DashboardComponent extends Component
{
    public function render(ReportRepository $reports, ProductRepository $products): View
    {
        $summary = $reports->dashboardSummary();
        $salesChart = $reports->monthlySalesChart(6);
        $financeChart = $reports->monthlyFinanceChart(6);
        $topProducts = $reports->topProducts(5);
        $lowStock = $products->lowStock(5, 5);

        return view('livewire.dashboard-component', compact(
            'summary',
            'salesChart',
            'financeChart',
            'topProducts',
            'lowStock',
        ));
    }
}
