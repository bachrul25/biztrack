<?php

namespace App\Repositories;

use App\Models\Finance;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class ReportRepository
{
    public function salesPaginate(?string $dateFrom = null, ?string $dateTo = null, ?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Sale::query()
            ->with(['user', 'details.product'])
            ->when($dateFrom, fn ($q) => $q->whereDate('date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('date', '<=', $dateTo))
            ->when($search, fn ($q) => $q->where('invoice_number', 'like', "%{$search}%"))
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function salesCollection(?string $dateFrom = null, ?string $dateTo = null)
    {
        return Sale::query()
            ->with(['user', 'details.product'])
            ->when($dateFrom, fn ($q) => $q->whereDate('date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('date', '<=', $dateTo))
            ->orderBy('date')
            ->get();
    }

    public function financePaginate(?string $dateFrom = null, ?string $dateTo = null, ?string $type = null, ?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Finance::query()
            ->when($dateFrom, fn ($q) => $q->whereDate('date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('date', '<=', $dateTo))
            ->when($type, fn ($q) => $q->where('type', $type))
            ->when($search, fn ($q) => $q->where('description', 'like', "%{$search}%"))
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function financeCollection(?string $dateFrom = null, ?string $dateTo = null, ?string $type = null)
    {
        return Finance::query()
            ->when($dateFrom, fn ($q) => $q->whereDate('date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('date', '<=', $dateTo))
            ->when($type, fn ($q) => $q->where('type', $type))
            ->orderBy('date')
            ->get();
    }

    public function stockPaginate(?string $search = null, ?string $status = null, int $perPage = 10): LengthAwarePaginator
    {
        return Product::query()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($status === 'low', fn ($q) => $q->where('stock', '<', 5)->where('stock', '>', 0))
            ->when($status === 'out', fn ($q) => $q->where('stock', '<=', 0))
            ->when($status === 'safe', fn ($q) => $q->where('stock', '>=', 5))
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function stockCollection(?string $search = null, ?string $status = null)
    {
        return Product::query()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($status === 'low', fn ($q) => $q->where('stock', '<', 5)->where('stock', '>', 0))
            ->when($status === 'out', fn ($q) => $q->where('stock', '<=', 0))
            ->when($status === 'safe', fn ($q) => $q->where('stock', '>=', 5))
            ->orderBy('name')
            ->get();
    }

    public function dashboardSummary(): array
    {
        $today = now()->toDateString();
        $totalSales = (float) Sale::sum('total');
        $totalIncome = (float) Finance::income()->sum('amount');
        $totalExpense = (float) Finance::expense()->sum('amount');
        $productCount = (int) Product::count();
        $totalStock = (int) Product::sum('stock');
        $todayTransactions = (int) Sale::whereDate('date', $today)->count();

        return [
            'total_sales' => $totalSales,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net_profit' => $totalIncome - $totalExpense,
            'product_count' => $productCount,
            'total_stock' => $totalStock,
            'today_transactions' => $todayTransactions,
        ];
    }

    public function monthlySalesChart(int $months = 6): array
    {
        $end = now()->endOfMonth();
        $start = now()->subMonths($months - 1)->startOfMonth();
        $period = CarbonPeriod::create($start, '1 month', $end);

        $labels = [];
        $values = [];
        foreach ($period as $date) {
            $labels[] = $date->format('M Y');
            $values[] = (float) Sale::whereYear('date', $date->year)
                ->whereMonth('date', $date->month)
                ->sum('total');
        }

        return ['labels' => $labels, 'values' => $values];
    }

    public function monthlyFinanceChart(int $months = 6): array
    {
        $end = now()->endOfMonth();
        $start = now()->subMonths($months - 1)->startOfMonth();
        $period = CarbonPeriod::create($start, '1 month', $end);

        $labels = [];
        $income = [];
        $expense = [];
        foreach ($period as $date) {
            $labels[] = $date->format('M Y');
            $income[] = (float) Finance::income()
                ->whereYear('date', $date->year)
                ->whereMonth('date', $date->month)
                ->sum('amount');
            $expense[] = (float) Finance::expense()
                ->whereYear('date', $date->year)
                ->whereMonth('date', $date->month)
                ->sum('amount');
        }

        return ['labels' => $labels, 'income' => $income, 'expense' => $expense];
    }

    public function topProducts(int $limit = 5)
    {
        return SaleDetail::query()
            ->selectRaw('product_id, SUM(quantity) as total_qty, SUM(subtotal) as total_omzet')
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->get();
    }
}
