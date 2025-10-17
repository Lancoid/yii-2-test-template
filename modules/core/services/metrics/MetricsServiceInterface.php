<?php

declare(strict_types=1);

namespace app\modules\core\services\metrics;

/**
 * Interface for application metrics service.
 * Provides methods to record requests, errors, counters, gauges, and retrieve aggregated metrics.
 */
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
     * @param string $type Error type or class name
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
     *
     * @return array Aggregated metrics data
     */
    public function getMetrics(int $fromTimestamp, ?int $toTimestamp = null): array;

    /**
     * Get metrics summary (response times, error rates, etc.).
     *
     * @param int $minutes Time period in minutes (default 60)
     *
     * @return array{
     *     period_minutes: int,
     *     from: non-falsy-string,
     *     to: non-falsy-string,
     *     requests: array{
     *         total: 0|int<1, max>,
     *         by_status: array<int<1, max>>,
     *         by_method: array<int<1, max>>,
     *         by_route: array<int<1, max>>,
     *         response_times: array<string,null|float>,
     *     },
     *     errors: array{
     *         total: int<0, max>,
     *         by_type: array<int<1, max>>,
     *         by_route: array<int<1, max>>,
     *         error_rate: 0|float,
     *     },
     * } Metrics summary data
     */
    public function getSummary(int $minutes = 60): array;
}
