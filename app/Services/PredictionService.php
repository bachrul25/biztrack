<?php

namespace App\Services;

use App\Repositories\PredictionRepository;
use App\Repositories\SaleRepository;
use Carbon\Carbon;

/**
 * Time-series forecasting for sales quantity.
 *
 * Supported methods (all implemented with working formulas):
 *  - moving_average              (MA)
 *  - weighted_moving_average     (WMA)
 *  - single_exponential_smoothing (SES)
 *  - double_exponential_smoothing (Holt)
 *  - linear_trend                (Least-squares linear trend)
 *  - seasonal_index              (Classical decomposition: trend x seasonal index)
 *
 * ARIMA / SARIMA are described in README as a planned extension; the full
 * maximum-likelihood estimator is out of scope for a single PHP file.
 */
class PredictionService
{
    public const METHODS = [
        'moving_average' => 'Moving Average',
        'weighted_moving_average' => 'Weighted Moving Average',
        'single_exponential_smoothing' => 'Single Exponential Smoothing',
        'double_exponential_smoothing' => 'Double Exponential Smoothing (Holt)',
        'linear_trend' => 'Linear Trend Projection',
        'seasonal_index' => 'Seasonal Index Decomposition',
    ];

    public function __construct(
        private readonly SaleRepository $saleRepo,
        private readonly PredictionRepository $predictionRepo,
    ) {}

    /**
     * Fetch historical series from the DB and fill missing periods with 0.
     *
     * @return array List of ['period' => string, 'value' => float]
     */
    public function historicalSeries(string $periodType, ?int $productId = null, ?string $from = null, ?string $to = null): array
    {
        $raw = $this->saleRepo->salesTimeSeries($periodType, $productId, $from, $to);
        $map = [];
        foreach ($raw as $r) {
            $map[$r['period']] = (float) $r['value'];
        }

        // Build a complete period axis from first-observed period to last-observed
        // period so gaps (weeks / days with no sales) are filled with 0.
        if ($raw === []) {
            return [];
        }

        $periods = array_keys($map);
        $first = reset($periods);
        $last = end($periods);
        $axis = $this->periodAxis($first, $last, $periodType);

        $series = [];
        foreach ($axis as $p) {
            $series[] = ['period' => $p, 'value' => $map[$p] ?? 0.0];
        }

        return $series;
    }

    /**
     * Dispatch to the chosen method.
     *
     * @param  array  $series  Historical data from historicalSeries()
     * @param  int  $horizon  Number of future periods to forecast
     * @param  array  $options  Method-specific hyperparameters (window, alpha, beta, season_length, weights)
     * @return array{fitted: list<float|null>, forecast: list<float>, forecast_periods: list<string>}
     */
    public function forecast(string $method, array $series, int $horizon = 1, array $options = [], ?string $periodType = 'monthly'): array
    {
        $values = array_map(fn ($r) => (float) $r['value'], $series);
        $periods = array_map(fn ($r) => $r['period'], $series);
        $lastPeriod = end($periods) ?: now()->format('Y-m');

        $result = match ($method) {
            'moving_average' => $this->movingAverage($values, (int) ($options['window'] ?? 3), $horizon),
            'weighted_moving_average' => $this->weightedMovingAverage($values, $options['weights'] ?? [1, 2, 3], $horizon),
            'single_exponential_smoothing' => $this->singleExponentialSmoothing($values, (float) ($options['alpha'] ?? 0.4), $horizon),
            'double_exponential_smoothing' => $this->doubleExponentialSmoothing($values, (float) ($options['alpha'] ?? 0.4), (float) ($options['beta'] ?? 0.3), $horizon),
            'linear_trend' => $this->linearTrend($values, $horizon),
            'seasonal_index' => $this->seasonalIndex($values, (int) ($options['season_length'] ?? 7), $horizon),
            default => throw new \DomainException('Metode prediksi tidak dikenal: '.$method),
        };

        $result['forecast_periods'] = $this->nextPeriods($lastPeriod, $horizon, $periodType ?? 'monthly');

        return $result;
    }

    /**
     * Evaluate fitted forecast against actual values.
     *
     * @param  list<float>  $actual
     * @param  list<float|null>  $fitted
     */
    public function evaluate(array $actual, array $fitted): array
    {
        $n = min(count($actual), count($fitted));
        $errors = [];
        $absErrors = [];
        $sqErrors = [];
        $percErrors = [];
        for ($i = 0; $i < $n; $i++) {
            if ($fitted[$i] === null) {
                continue;
            }
            $a = (float) $actual[$i];
            $f = (float) $fitted[$i];
            $err = $a - $f;
            $errors[] = $err;
            $absErrors[] = abs($err);
            $sqErrors[] = $err * $err;
            if ($a != 0.0) {
                $percErrors[] = abs($err / $a);
            }
        }

        $count = count($errors);
        if ($count === 0) {
            return ['mad' => null, 'mse' => null, 'rmse' => null, 'mape' => null, 'n' => 0];
        }

        $mad = array_sum($absErrors) / $count;
        $mse = array_sum($sqErrors) / $count;
        $rmse = sqrt($mse);
        $mape = $percErrors === [] ? null : (array_sum($percErrors) / count($percErrors)) * 100.0;

        return [
            'mad' => round($mad, 4),
            'mse' => round($mse, 4),
            'rmse' => round($rmse, 4),
            'mape' => $mape === null ? null : round($mape, 4),
            'n' => $count,
        ];
    }

    public function stockRecommendation(float $forecastTotal, float $safetyPct = 0.15): array
    {
        $forecast = max(0, round($forecastTotal));
        $safety = (int) ceil($forecast * $safetyPct);
        $recommended = $forecast + $safety;

        return [
            'forecast' => (int) $forecast,
            'safety_stock' => $safety,
            'safety_percentage' => $safetyPct * 100,
            'recommended_stock' => (int) $recommended,
        ];
    }

    public function saveResults(string $method, string $periodType, array $forecast, array $forecastPeriods, array $metrics, ?string $description = null): void
    {
        foreach ($forecast as $i => $value) {
            $this->predictionRepo->save([
                'method' => $method,
                'period_type' => $periodType,
                'prediction_date' => $this->periodToDate($forecastPeriods[$i] ?? null, $periodType),
                'actual_value' => null,
                'predicted_value' => round((float) $value, 4),
                'error_value' => null,
                'mape' => $metrics['mape'] ?? null,
                'mad' => $metrics['mad'] ?? null,
                'mse' => $metrics['mse'] ?? null,
                'rmse' => $metrics['rmse'] ?? null,
                'description' => $description,
            ]);
        }
    }

    // ---------- Algorithms ----------

    /** @return array{fitted: list<float|null>, forecast: list<float>} */
    private function movingAverage(array $values, int $window, int $horizon): array
    {
        $window = max(2, $window);
        $n = count($values);
        $fitted = array_fill(0, $n, null);
        for ($i = $window; $i < $n; $i++) {
            $fitted[$i] = array_sum(array_slice($values, $i - $window, $window)) / $window;
        }

        $forecast = [];
        $buffer = $values;
        for ($h = 0; $h < $horizon; $h++) {
            $slice = array_slice($buffer, -$window);
            $next = array_sum($slice) / count($slice);
            $forecast[] = round($next, 4);
            $buffer[] = $next;
        }

        return ['fitted' => $fitted, 'forecast' => $forecast];
    }

    /**
     * Weighted moving average. Weights length determines window; last weight
     * applies to the most recent observation.
     */
    private function weightedMovingAverage(array $values, array $weights, int $horizon): array
    {
        if ($weights === []) {
            $weights = [1, 2, 3];
        }
        $weights = array_values(array_map('floatval', $weights));
        $sumW = array_sum($weights);
        if ($sumW <= 0) {
            throw new \DomainException('Total bobot harus lebih dari 0.');
        }
        $window = count($weights);
        $n = count($values);
        $fitted = array_fill(0, $n, null);
        for ($i = $window; $i < $n; $i++) {
            $slice = array_slice($values, $i - $window, $window);
            $sum = 0;
            foreach ($slice as $k => $v) {
                $sum += $v * $weights[$k];
            }
            $fitted[$i] = $sum / $sumW;
        }
        $forecast = [];
        $buffer = $values;
        for ($h = 0; $h < $horizon; $h++) {
            $slice = array_slice($buffer, -$window);
            $sum = 0;
            $effWeights = count($slice) === $window ? $weights : array_slice($weights, -count($slice));
            $effSum = array_sum($effWeights);
            foreach ($slice as $k => $v) {
                $sum += $v * $effWeights[$k];
            }
            $next = $effSum > 0 ? $sum / $effSum : 0.0;
            $forecast[] = round($next, 4);
            $buffer[] = $next;
        }

        return ['fitted' => $fitted, 'forecast' => $forecast];
    }

    private function singleExponentialSmoothing(array $values, float $alpha, int $horizon): array
    {
        $alpha = max(0.01, min(1.0, $alpha));
        $n = count($values);
        if ($n === 0) {
            return ['fitted' => [], 'forecast' => array_fill(0, $horizon, 0.0)];
        }
        $fitted = array_fill(0, $n, null);
        $fitted[0] = $values[0];
        for ($i = 1; $i < $n; $i++) {
            $fitted[$i] = $alpha * $values[$i - 1] + (1 - $alpha) * $fitted[$i - 1];
        }
        $last = $alpha * $values[$n - 1] + (1 - $alpha) * $fitted[$n - 1];
        $forecast = array_fill(0, $horizon, round($last, 4));

        return ['fitted' => $fitted, 'forecast' => $forecast];
    }

    /** Holt linear method */
    private function doubleExponentialSmoothing(array $values, float $alpha, float $beta, int $horizon): array
    {
        $alpha = max(0.01, min(1.0, $alpha));
        $beta = max(0.01, min(1.0, $beta));
        $n = count($values);
        if ($n < 2) {
            return ['fitted' => array_fill(0, $n, null), 'forecast' => array_fill(0, $horizon, $values[0] ?? 0.0)];
        }
        $level = $values[0];
        $trend = $values[1] - $values[0];
        $fitted = array_fill(0, $n, null);
        $fitted[0] = $level;
        $fitted[1] = $level + $trend;
        for ($i = 1; $i < $n; $i++) {
            $prevLevel = $level;
            $level = $alpha * $values[$i] + (1 - $alpha) * ($level + $trend);
            $trend = $beta * ($level - $prevLevel) + (1 - $beta) * $trend;
            if ($i + 1 < $n) {
                $fitted[$i + 1] = $level + $trend;
            }
        }
        $forecast = [];
        for ($h = 1; $h <= $horizon; $h++) {
            $forecast[] = round($level + $h * $trend, 4);
        }

        return ['fitted' => $fitted, 'forecast' => $forecast];
    }

    private function linearTrend(array $values, int $horizon): array
    {
        $n = count($values);
        if ($n < 2) {
            return ['fitted' => array_fill(0, $n, null), 'forecast' => array_fill(0, $horizon, $values[0] ?? 0.0)];
        }
        $sumX = 0;
        $sumY = 0;
        $sumXY = 0;
        $sumXX = 0;
        for ($i = 0; $i < $n; $i++) {
            $x = $i + 1;
            $y = $values[$i];
            $sumX += $x;
            $sumY += $y;
            $sumXY += $x * $y;
            $sumXX += $x * $x;
        }
        $denom = $n * $sumXX - $sumX * $sumX;
        $b = $denom == 0 ? 0 : ($n * $sumXY - $sumX * $sumY) / $denom;
        $a = ($sumY - $b * $sumX) / $n;
        $fitted = [];
        for ($i = 0; $i < $n; $i++) {
            $fitted[] = $a + $b * ($i + 1);
        }
        $forecast = [];
        for ($h = 1; $h <= $horizon; $h++) {
            $forecast[] = round($a + $b * ($n + $h), 4);
        }

        return ['fitted' => $fitted, 'forecast' => $forecast];
    }

    /**
     * Classical decomposition with multiplicative seasonal index.
     * Uses linear trend for deseasonalised series.
     */
    private function seasonalIndex(array $values, int $seasonLength, int $horizon): array
    {
        $n = count($values);
        if ($n < $seasonLength * 2 || $seasonLength < 2) {
            // Fall back to linear trend when there isn't enough data for seasonality
            return $this->linearTrend($values, $horizon);
        }
        // 1. Compute trend via centered moving average
        $trend = array_fill(0, $n, null);
        $half = intdiv($seasonLength, 2);
        for ($i = $half; $i < $n - $half; $i++) {
            $window = array_slice($values, $i - $half, $seasonLength);
            if ($seasonLength % 2 === 0) {
                // Centered MA for even season: average two consecutive MAs
                $w2 = array_slice($values, $i - $half + 1, $seasonLength);
                $trend[$i] = (array_sum($window) / $seasonLength + array_sum($w2) / $seasonLength) / 2;
            } else {
                $trend[$i] = array_sum($window) / $seasonLength;
            }
        }
        // 2. Seasonal ratios (value / trend) grouped by season slot
        $ratios = array_fill(0, $seasonLength, []);
        for ($i = 0; $i < $n; $i++) {
            if ($trend[$i] !== null && $trend[$i] != 0.0) {
                $ratios[$i % $seasonLength][] = $values[$i] / $trend[$i];
            }
        }
        $seasonal = [];
        foreach ($ratios as $k => $rs) {
            $seasonal[$k] = $rs === [] ? 1.0 : array_sum($rs) / count($rs);
        }
        // Normalise so seasonal indices average to 1
        $avg = array_sum($seasonal) / $seasonLength;
        if ($avg > 0) {
            foreach ($seasonal as $k => $v) {
                $seasonal[$k] = $v / $avg;
            }
        }
        // 3. Deseasonalise and fit linear trend
        $deseas = [];
        for ($i = 0; $i < $n; $i++) {
            $s = $seasonal[$i % $seasonLength];
            $deseas[] = $s > 0 ? $values[$i] / $s : $values[$i];
        }
        $trendFit = $this->linearTrend($deseas, $horizon);
        $fitted = [];
        foreach ($trendFit['fitted'] as $i => $v) {
            $fitted[] = $v === null ? null : $v * $seasonal[$i % $seasonLength];
        }
        $forecast = [];
        foreach ($trendFit['forecast'] as $h => $v) {
            $idx = ($n + $h) % $seasonLength;
            $forecast[] = round($v * $seasonal[$idx], 4);
        }

        return ['fitted' => $fitted, 'forecast' => $forecast];
    }

    // ---------- Period axis helpers ----------

    private function periodAxis(string $first, string $last, string $periodType): array
    {
        $axis = [];
        if ($periodType === 'monthly') {
            $start = Carbon::createFromFormat('Y-m', $first)->startOfMonth();
            $end = Carbon::createFromFormat('Y-m', $last)->startOfMonth();
            while ($start->lte($end)) {
                $axis[] = $start->format('Y-m');
                $start->addMonth();
            }
        } elseif ($periodType === 'weekly') {
            // Format 'Y-Www' -> approximate by using first week day
            $startDate = $this->weekToDate($first);
            $endDate = $this->weekToDate($last);
            $cur = $startDate->copy();
            while ($cur->lte($endDate)) {
                $axis[] = $cur->format('Y').'-W'.str_pad((string) $cur->weekOfYear, 2, '0', STR_PAD_LEFT);
                $cur->addWeek();
            }
            $axis = array_values(array_unique($axis));
        } else {
            $start = Carbon::parse($first);
            $end = Carbon::parse($last);
            while ($start->lte($end)) {
                $axis[] = $start->format('Y-m-d');
                $start->addDay();
            }
        }

        return $axis;
    }

    private function nextPeriods(string $lastPeriod, int $horizon, string $periodType): array
    {
        $out = [];
        if ($periodType === 'monthly') {
            $cur = Carbon::createFromFormat('Y-m', $lastPeriod)->startOfMonth();
            for ($h = 0; $h < $horizon; $h++) {
                $cur->addMonth();
                $out[] = $cur->format('Y-m');
            }
        } elseif ($periodType === 'weekly') {
            $cur = $this->weekToDate($lastPeriod);
            for ($h = 0; $h < $horizon; $h++) {
                $cur->addWeek();
                $out[] = $cur->format('Y').'-W'.str_pad((string) $cur->weekOfYear, 2, '0', STR_PAD_LEFT);
            }
        } else {
            $cur = Carbon::parse($lastPeriod);
            for ($h = 0; $h < $horizon; $h++) {
                $cur->addDay();
                $out[] = $cur->format('Y-m-d');
            }
        }

        return $out;
    }

    private function weekToDate(string $label): Carbon
    {
        // label like 2026-W04 (best effort)
        if (preg_match('/^(\d{4})-W(\d{1,2})$/', $label, $m)) {
            return Carbon::now()->setISODate((int) $m[1], (int) $m[2])->startOfWeek();
        }

        return Carbon::parse($label);
    }

    private function periodToDate(?string $period, string $periodType): string
    {
        if (! $period) {
            return now()->toDateString();
        }
        if ($periodType === 'monthly') {
            return Carbon::createFromFormat('Y-m', $period)->startOfMonth()->toDateString();
        }
        if ($periodType === 'weekly') {
            return $this->weekToDate($period)->toDateString();
        }

        return Carbon::parse($period)->toDateString();
    }
}
