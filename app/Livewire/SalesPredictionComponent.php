<?php

namespace App\Livewire;

use App\Models\Product;
use App\Repositories\PredictionRepository;
use App\Services\PredictionService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SalesPredictionComponent extends Component
{
    public string $periodType = 'monthly'; // daily | weekly | monthly

    public string $method = 'linear_trend';

    public int $horizon = 3;

    public ?int $productId = null;

    public int $window = 3;

    public string $weights = '1,2,3';

    public float $alpha = 0.4;

    public float $beta = 0.3;

    public int $seasonLength = 7;

    public float $safetyPct = 0.15;

    public bool $hasRun = false;

    public array $historical = [];

    public array $forecast = [];

    public array $forecastPeriods = [];

    public array $fitted = [];

    public array $metrics = [];

    public array $recommendation = [];

    public string $conclusion = '';

    public function run(): void
    {
        $this->validate([
            'periodType' => ['required', 'in:daily,weekly,monthly'],
            'method' => ['required', 'in:moving_average,weighted_moving_average,single_exponential_smoothing,double_exponential_smoothing,linear_trend,seasonal_index'],
            'horizon' => ['required', 'integer', 'min:1', 'max:24'],
            'window' => ['required', 'integer', 'min:2', 'max:12'],
            'alpha' => ['required', 'numeric', 'min:0.01', 'max:1'],
            'beta' => ['required', 'numeric', 'min:0.01', 'max:1'],
            'seasonLength' => ['required', 'integer', 'min:2', 'max:52'],
            'safetyPct' => ['required', 'numeric', 'min:0', 'max:1'],
        ]);

        /** @var PredictionService $svc */
        $svc = app(PredictionService::class);
        $hist = $svc->historicalSeries($this->periodType, $this->productId ?: null);

        if (count($hist) < 2) {
            $this->dispatch('swal', icon: 'warning', title: 'Data tidak cukup', text: 'Butuh minimal 2 periode data penjualan untuk memprediksi.');
            $this->hasRun = false;

            return;
        }

        $weights = array_values(array_filter(array_map('trim', explode(',', $this->weights)), fn ($v) => $v !== ''));
        $options = [
            'window' => $this->window,
            'weights' => array_map('floatval', $weights),
            'alpha' => $this->alpha,
            'beta' => $this->beta,
            'season_length' => $this->seasonLength,
        ];

        $result = $svc->forecast($this->method, $hist, $this->horizon, $options, $this->periodType);
        $metrics = $svc->evaluate(array_column($hist, 'value'), $result['fitted']);
        $totalForecast = array_sum($result['forecast']);
        $recommendation = $svc->stockRecommendation($totalForecast, $this->safetyPct);

        $this->historical = $hist;
        $this->forecast = $result['forecast'];
        $this->forecastPeriods = $result['forecast_periods'];
        $this->fitted = $result['fitted'];
        $this->metrics = $metrics;
        $this->recommendation = $recommendation;
        $this->conclusion = $this->buildConclusion($hist, $result, $metrics, $recommendation);
        $this->hasRun = true;

        try {
            $svc->saveResults(
                $this->method,
                $this->periodType,
                $result['forecast'],
                $result['forecast_periods'],
                $metrics,
                $this->conclusion,
            );
        } catch (\Throwable $e) {
            // Non-fatal: showing results is still valuable even if persistence fails
        }

        $this->dispatch('prediction-updated', chart: $this->chartPayload());
    }

    public function getChartPayloadProperty(): array
    {
        return $this->chartPayload();
    }

    private function chartPayload(): array
    {
        $histPeriods = array_column($this->historical, 'period');
        $histValues = array_column($this->historical, 'value');
        $labels = array_merge($histPeriods, $this->forecastPeriods);
        $actual = array_merge($histValues, array_fill(0, count($this->forecast), null));
        $predicted = array_merge(
            array_map(fn ($v) => $v === null ? null : round((float) $v, 2), $this->fitted),
            $this->forecast,
        );

        return compact('labels', 'actual', 'predicted');
    }

    private function buildConclusion(array $hist, array $result, array $metrics, array $recommendation): string
    {
        $methodName = PredictionService::METHODS[$this->method] ?? $this->method;
        $productName = $this->productId ? optional(Product::find($this->productId))->name : 'seluruh produk';
        $forecastTotal = array_sum($result['forecast']);
        $mape = $metrics['mape'] !== null ? number_format($metrics['mape'], 2).'%' : 'tidak tersedia';

        return sprintf(
            'Dengan metode %s, penjualan %s untuk %d periode %s ke depan diprediksi sebanyak %s unit (rata-rata MAPE %s). Sistem merekomendasikan stok minimal %d unit (termasuk safety stock %d%%).',
            $methodName,
            $productName,
            $this->horizon,
            $this->periodType,
            number_format($forecastTotal, 0, ',', '.'),
            $mape,
            $recommendation['recommended_stock'],
            $recommendation['safety_percentage'],
        );
    }

    public function render()
    {
        return view('livewire.sales-prediction-component', [
            'products' => Product::orderBy('name')->get(),
            'methods' => PredictionService::METHODS,
            'recentPredictions' => app(PredictionRepository::class)->latest(10),
        ]);
    }
}
