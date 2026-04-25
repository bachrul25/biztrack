<?php

namespace App\Repositories;

use App\Models\PredictionResult;
use Illuminate\Database\Eloquent\Collection;

class PredictionRepository
{
    public function save(array $data): PredictionResult
    {
        return PredictionResult::create($data);
    }

    public function saveMany(array $rows): void
    {
        foreach ($rows as $row) {
            PredictionResult::create($row);
        }
    }

    public function latest(int $limit = 50): Collection
    {
        return PredictionResult::orderByDesc('created_at')->limit($limit)->get();
    }

    public function byMethod(string $method, int $limit = 50): Collection
    {
        return PredictionResult::where('method', $method)
            ->orderByDesc('prediction_date')
            ->limit($limit)
            ->get();
    }

    public function clearRecent(): void
    {
        PredictionResult::query()->delete();
    }
}
