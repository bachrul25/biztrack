<?php

namespace App\Repositories;

use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SaleRepository
{
    public function paginate(?string $search = null, ?string $dateFrom = null, ?string $dateTo = null, int $perPage = 10): LengthAwarePaginator
    {
        return Sale::query()
            ->with(['user', 'items.product'])
            ->when($search, fn ($q) => $q->where('invoice_number', 'like', "%{$search}%"))
            ->when($dateFrom, fn ($q) => $q->whereDate('sale_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('sale_date', '<=', $dateTo))
            ->orderByDesc('sale_date')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function find(int $id): ?Sale
    {
        return Sale::with(['user', 'items.product'])->find($id);
    }

    public function createSale(array $saleData): Sale
    {
        return Sale::create($saleData);
    }

    public function createItem(array $itemData): SaleItem
    {
        return SaleItem::create($itemData);
    }

    public function delete(Sale $sale): bool
    {
        return (bool) $sale->delete();
    }

    public function totalRevenue(?string $dateFrom = null, ?string $dateTo = null): float
    {
        return (float) Sale::query()
            ->when($dateFrom, fn ($q) => $q->whereDate('sale_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('sale_date', '<=', $dateTo))
            ->sum('total_amount');
    }

    public function totalCost(?string $dateFrom = null, ?string $dateTo = null): float
    {
        return (float) Sale::query()
            ->when($dateFrom, fn ($q) => $q->whereDate('sale_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('sale_date', '<=', $dateTo))
            ->sum('total_cost');
    }

    public function grossProfit(?string $dateFrom = null, ?string $dateTo = null): float
    {
        return (float) Sale::query()
            ->when($dateFrom, fn ($q) => $q->whereDate('sale_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('sale_date', '<=', $dateTo))
            ->sum('gross_profit');
    }

    public function countSales(?string $dateFrom = null, ?string $dateTo = null): int
    {
        return Sale::query()
            ->when($dateFrom, fn ($q) => $q->whereDate('sale_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('sale_date', '<=', $dateTo))
            ->count();
    }

    /** Daily revenue series [['date' => 'YYYY-MM-DD', 'total' => ...], ...] */
    public function dailySeries(string $dateFrom, string $dateTo): array
    {
        return Sale::query()
            ->selectRaw('DATE(sale_date) as d, SUM(total_amount) as total, COUNT(*) as n')
            ->whereDate('sale_date', '>=', $dateFrom)
            ->whereDate('sale_date', '<=', $dateTo)
            ->groupBy('d')
            ->orderBy('d')
            ->get()
            ->map(fn ($r) => ['date' => $r->d, 'total' => (float) $r->total, 'count' => (int) $r->n])
            ->toArray();
    }

    /** Monthly series, last N months */
    public function monthlySeries(int $months = 12): array
    {
        $start = now()->startOfMonth()->subMonths($months - 1);
        $driver = DB::connection()->getDriverName();
        $ym = $driver === 'sqlite'
            ? "strftime('%Y-%m', sale_date)"
            : "DATE_FORMAT(sale_date, '%Y-%m')";

        return Sale::query()
            ->selectRaw("$ym as ym, SUM(total_amount) as total, COUNT(*) as n")
            ->whereDate('sale_date', '>=', $start->toDateString())
            ->groupBy('ym')
            ->orderBy('ym')
            ->get()
            ->map(fn ($r) => ['period' => $r->ym, 'total' => (float) $r->total, 'count' => (int) $r->n])
            ->toArray();
    }

    /**
     * Aggregate quantity sold per period (for time series prediction).
     * period_type: daily | weekly | monthly
     * Returns array of ['period' => string, 'value' => float]
     */
    public function salesTimeSeries(string $periodType, ?int $productId = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $driver = DB::connection()->getDriverName();
        $dateExpr = match ($periodType) {
            'weekly' => $driver === 'sqlite' ? "strftime('%Y-W%W', s.sale_date)" : "DATE_FORMAT(s.sale_date, '%x-W%v')",
            'monthly' => $driver === 'sqlite' ? "strftime('%Y-%m', s.sale_date)" : "DATE_FORMAT(s.sale_date, '%Y-%m')",
            default => $driver === 'sqlite' ? "strftime('%Y-%m-%d', s.sale_date)" : "DATE_FORMAT(s.sale_date, '%Y-%m-%d')",
        };

        $query = DB::table('sale_items as si')
            ->join('sales as s', 's.id', '=', 'si.sale_id')
            ->selectRaw("$dateExpr as period, SUM(si.quantity) as qty, SUM(si.subtotal) as revenue")
            ->when($productId, fn ($q) => $q->where('si.product_id', $productId))
            ->when($dateFrom, fn ($q) => $q->whereDate('s.sale_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('s.sale_date', '<=', $dateTo))
            ->groupBy('period')
            ->orderBy('period');

        return $query->get()->map(fn ($r) => [
            'period' => $r->period,
            'value' => (float) $r->qty,
            'revenue' => (float) $r->revenue,
        ])->toArray();
    }

    /** Top selling products by quantity */
    public function topProducts(int $limit = 5, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        return DB::table('sale_items as si')
            ->join('products as p', 'p.id', '=', 'si.product_id')
            ->join('sales as s', 's.id', '=', 'si.sale_id')
            ->selectRaw('p.id, p.name, SUM(si.quantity) as qty, SUM(si.subtotal) as revenue')
            ->when($dateFrom, fn ($q) => $q->whereDate('s.sale_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('s.sale_date', '<=', $dateTo))
            ->groupBy('p.id', 'p.name')
            ->orderByDesc('qty')
            ->limit($limit)
            ->get()
            ->map(fn ($r) => [
                'id' => (int) $r->id,
                'name' => $r->name,
                'qty' => (int) $r->qty,
                'revenue' => (float) $r->revenue,
            ])->toArray();
    }
}
