<?php

declare(strict_types=1);

namespace app\modules\core\services\metrics;

interface MetricsServiceInterface
{
    /**
     * Record request metric.
     *
     * @param string $route Route name (e.g., 'user/view', 'api/v1/users')
     * @param float $duration Duration in milliseconds
     * @param int $statusCode HTTP status code
     * @param string $method HTTP method (GET, POST, etc.)
     */
    public function recordRequest(string $route, float $duration, int $statusCode, string $method): void;

    /**
     * Record error occurrence.
     *
     * @param string $type Error type/class name
     * @param string $message Error message
     * @param null|string $route Optional route where error occurred
     * @param array $context Additional context
     */
    public function recordError(string $type, string $message, ?string $route = null, array $context = []): void;

    /**
     * Increment a counter metric.
     *
     * @param string $name Metric name
     * @param int $value Value to add (default 1)
     * @param array $tags Optional tags for grouping
     */
    public function increment(string $name, int $value = 1, array $tags = []): void;

    /**
     * Record a gauge metric (current value).
     *
     * @param string $name Metric name
     * @param float $value Current value
     * @param array $tags Optional tags
     */
    public function gauge(string $name, float $value, array $tags = []): void;

    /**
     * Get aggregated metrics for a time period.
     *
     * @param int $fromTimestamp Start timestamp
     * @param null|int $toTimestamp End timestamp (null = now)
     */
    public function getMetrics(int $fromTimestamp, ?int $toTimestamp = null): array;

    /**
     * Get metrics summary (response times, error rates, etc.).
     *
     * @param int $minutes Time period in minutes (default 60)
     */
    public function getSummary(int $minutes = 60): array;

    /**
     * Flush metrics to storage.
     */
    public function flush(): void;
}
