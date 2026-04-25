<?php

namespace App\Repositories;

use App\Models\Finance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class FinanceRepository
{
    public function paginate(?string $type = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Finance::query()
            ->when($type, fn ($q) => $q->where('type', $type))
            ->when($dateFrom, fn ($q) => $q->whereDate('transaction_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('transaction_date', '<=', $dateTo))
            ->when($search, fn ($q) => $q->where(function ($inner) use ($search) {
                $inner->where('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            }))
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function find(int $id): ?Finance
    {
        return Finance::find($id);
    }

    public function create(array $data): Finance
    {
        return Finance::create($data);
    }

    public function update(Finance $finance, array $data): Finance
    {
        $finance->update($data);

        return $finance->fresh();
    }

    public function delete(Finance $finance): bool
    {
        return (bool) $finance->delete();
    }

    public function totalIncome(?string $dateFrom = null, ?string $dateTo = null): float
    {
        return (float) Finance::income()
            ->when($dateFrom, fn ($q) => $q->whereDate('transaction_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('transaction_date', '<=', $dateTo))
            ->sum('amount');
    }

    public function totalExpense(?string $dateFrom = null, ?string $dateTo = null): float
    {
        return (float) Finance::expense()
            ->when($dateFrom, fn ($q) => $q->whereDate('transaction_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('transaction_date', '<=', $dateTo))
            ->sum('amount');
    }

    public function monthlyIncomeExpense(int $months = 6): array
    {
        $start = now()->startOfMonth()->subMonths($months - 1);
        $driver = DB::connection()->getDriverName();
        $ym = $driver === 'sqlite'
            ? "strftime('%Y-%m', transaction_date)"
            : "DATE_FORMAT(transaction_date, '%Y-%m')";

        return Finance::query()
            ->selectRaw("$ym as ym, type, SUM(amount) as total")
            ->whereDate('transaction_date', '>=', $start->toDateString())
            ->groupBy('ym', 'type')
            ->orderBy('ym')
            ->get()
            ->groupBy('ym')
            ->map(function ($rows) {
                $income = (float) optional($rows->firstWhere('type', 'income'))->total;
                $expense = (float) optional($rows->firstWhere('type', 'expense'))->total;

                return ['income' => $income, 'expense' => $expense];
            })
            ->toArray();
    }
}
