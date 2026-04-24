<?php

namespace App\Repositories;

use App\Models\Finance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FinanceRepository
{
    public function paginate(?string $search = null, ?string $type = null, ?string $dateFrom = null, ?string $dateTo = null, int $perPage = 10): LengthAwarePaginator
    {
        return Finance::query()
            ->when($search, fn ($q) => $q->where('description', 'like', "%{$search}%")->orWhere('source', 'like', "%{$search}%"))
            ->when($type, fn ($q) => $q->where('type', $type))
            ->when($dateFrom, fn ($q) => $q->whereDate('date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('date', '<=', $dateTo))
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function create(array $data): Finance
    {
        return Finance::create($data);
    }

    public function update(Finance $finance, array $data): Finance
    {
        $finance->update($data);
        return $finance;
    }

    public function delete(Finance $finance): void
    {
        $finance->delete();
    }

    public function totalIncome(?string $dateFrom = null, ?string $dateTo = null): float
    {
        return (float) Finance::income()
            ->when($dateFrom, fn ($q) => $q->whereDate('date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('date', '<=', $dateTo))
            ->sum('amount');
    }

    public function totalExpense(?string $dateFrom = null, ?string $dateTo = null): float
    {
        return (float) Finance::expense()
            ->when($dateFrom, fn ($q) => $q->whereDate('date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('date', '<=', $dateTo))
            ->sum('amount');
    }

    public function netProfit(?string $dateFrom = null, ?string $dateTo = null): float
    {
        return $this->totalIncome($dateFrom, $dateTo) - $this->totalExpense($dateFrom, $dateTo);
    }
}
