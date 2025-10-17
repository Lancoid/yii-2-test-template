<?php

declare(strict_types=1);

namespace app\modules\core\services\metrics;

use app\modules\core\services\metrics\storage\MetricsStorageInterface;

class MetricsService implements MetricsServiceInterface
{
    private const string TYPE_REQUEST = 'request';
    private const string TYPE_ERROR = 'error';
    private const string TYPE_COUNTER = 'counter';
    private const string TYPE_GAUGE = 'gauge';

    private MetricsStorageInterface $metricsStorage;
    private bool $enabled;

    public function __construct(MetricsStorageInterface $metricsStorage, bool $enabled = true)
    {
        $this->metricsStorage = $metricsStorage;
        $this->enabled = $enabled;
    }

    public function recordRequest(string $route, float $duration, int $statusCode, string $method): void
    {
        if (!$this->enabled) {
            return;
        }

        $this->metricsStorage->store(self::TYPE_REQUEST, [
            'route' => $route,
            'duration' => $duration,
            'status_code' => $statusCode,
            'method' => $method,
            'timestamp' => $this->now(),
            'memory_peak' => memory_get_peak_usage(true),
        ]);
    }

    public function recordError(string $type, string $message, ?string $route = null, array $context = []): void
    {
        if (!$this->enabled) {
            return;
        }

        $this->metricsStorage->store(self::TYPE_ERROR, [
            'type' => $type,
            'message' => $message,
            'route' => $route,
            'context' => $context,
            'timestamp' => $this->now(),
        ]);
    }

    public function increment(string $name, int $value = 1, array $tags = []): void
    {
        if (!$this->enabled) {
            return;
        }

        $this->metricsStorage->store(self::TYPE_COUNTER, [
            'name' => $name,
            'value' => $value,
            'tags' => $tags,
            'timestamp' => $this->now(),
        ]);
    }

    public function gauge(string $name, float $value, array $tags = []): void
    {
        if (!$this->enabled) {
            return;
        }

        $this->metricsStorage->store(self::TYPE_GAUGE, [
            'name' => $name,
            'value' => $value,
            'tags' => $tags,
            'timestamp' => $this->now(),
        ]);
    }

    public function getMetrics(int $fromTimestamp, ?int $toTimestamp = null): array
    {
        return $this->metricsStorage->retrieve($fromTimestamp, $toTimestamp);
    }

    public function getSummary(int $minutes = 60): array
    {
        $fromTimestamp = $this->now() - ($minutes * 60);
        $metrics = $this->metricsStorage->retrieve($fromTimestamp);

        $summary = [
            'period_minutes' => $minutes,
            'from' => date('Y-m-d H:i:s', $fromTimestamp),
            'to' => date('Y-m-d H:i:s', $this->now()),
            'requests' => [
                'total' => 0,
                'by_status' => [],
                'by_method' => [],
                'by_route' => [],
                'response_times' => [],
            ],
            'errors' => [
                'total' => 0,
                'by_type' => [],
                'by_route' => [],
                'error_rate' => 0,
            ],
        ];

        $durations = [];

        foreach ($metrics as $metric) {
            $type = $metric['type'] ?? null;

            if (self::TYPE_REQUEST === $type) {
                ++$summary['requests']['total'];

                $statusCode = $metric['status_code'] ?? 0;
                $method = $metric['method'] ?? 'UNKNOWN';
                $route = $metric['route'] ?? 'unknown';

                $summary['requests']['by_status'][$statusCode] = ($summary['requests']['by_status'][$statusCode] ?? 0) + 1;
                $summary['requests']['by_method'][$method] = ($summary['requests']['by_method'][$method] ?? 0) + 1;
                $summary['requests']['by_route'][$route] = ($summary['requests']['by_route'][$route] ?? 0) + 1;

                if (isset($metric['duration'])) {
                    $durations[] = $metric['duration'];
                }
            } elseif (self::TYPE_ERROR === $type) {
                ++$summary['errors']['total'];

                $errorType = $metric['type'] ?? 'Unknown';
                $route = $metric['route'] ?? 'unknown';

                $summary['errors']['by_type'][$errorType] = ($summary['errors']['by_type'][$errorType] ?? 0) + 1;

                if ($route) {
                    $summary['errors']['by_route'][$route] = ($summary['errors']['by_route'][$route] ?? 0) + 1;
                }
            }
        }

        /** @var array<float> $durations */
        $summary['requests']['response_times'] = $this->calculateResponseStats($durations);

        if ($summary['requests']['total'] > 0) {
            $summary['errors']['error_rate'] = round(
                ($summary['errors']['total'] / $summary['requests']['total']) * 100,
                2
            );
        }

        return $summary;
    }

    private function now(): int
    {
        return time();
    }

    /**
     * @param array<float> $durations
     *
     * @return array<string, null|float>
     */
    private function calculateResponseStats(array $durations): array
    {
        if (empty($durations)) {
            return [
                'min' => null,
                'max' => null,
                'avg' => null,
                'p50' => null,
                'p95' => null,
                'p99' => null,
            ];
        }

        sort($durations);

        return [
            'min' => round(min($durations), 2),
            'max' => round(max($durations), 2),
            'avg' => round(array_sum($durations) / count($durations), 2),
            'p50' => round($this->percentile($durations, 50), 2),
            'p95' => round($this->percentile($durations, 95), 2),
            'p99' => round($this->percentile($durations, 99), 2),
        ];
    }

    /**
     * @param array<float> $sorted
     */
    private function percentile(array $sorted, int $percentile): float
    {
        if (empty($sorted)) {
            return 0.0;
        }
        $index = ($percentile / 100) * (count($sorted) - 1);
        $lower = floor($index);
        $upper = ceil($index);
        $fraction = $index - $lower;

        if ($lower === $upper) {
            return $sorted[(int)$lower];
        }

        return $sorted[(int)$lower] * (1 - $fraction) + $sorted[(int)$upper] * $fraction;
    }
}
