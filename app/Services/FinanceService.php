<?php

namespace App\Services;

use App\Models\Finance;
use App\Repositories\FinanceRepository;

class FinanceService
{
    public function __construct(private readonly FinanceRepository $repo) {}

    public function create(array $data): Finance
    {
        return $this->repo->create($this->normalize($data));
    }

    public function update(Finance $finance, array $data): Finance
    {
        return $this->repo->update($finance, $this->normalize($data));
    }

    public function delete(Finance $finance): bool
    {
        if ($finance->source === 'sale') {
            throw new \DomainException('Data keuangan yang bersumber dari penjualan tidak bisa dihapus manual. Hapus transaksi penjualan terkait.');
        }

        return $this->repo->delete($finance);
    }

    public function netProfit(?string $dateFrom = null, ?string $dateTo = null): float
    {
        return $this->repo->totalIncome($dateFrom, $dateTo) - $this->repo->totalExpense($dateFrom, $dateTo);
    }

    private function normalize(array $data): array
    {
        return [
            'type' => $data['type'],
            'category' => $data['category'] ?? null,
            'description' => trim((string) $data['description']),
            'amount' => (float) $data['amount'],
            'transaction_date' => $data['transaction_date'] ?? now()->toDateString(),
            'source' => $data['source'] ?? 'manual',
            'reference_id' => $data['reference_id'] ?? null,
        ];
    }
}
