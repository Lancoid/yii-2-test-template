<?php

declare(strict_types=1);

namespace app\modules\core\services\metrics\storage;

interface MetricsStorageInterface
{
    /**
     * Store a metric point.
     *
     * @param string $type Metric type (request, error, counter, gauge)
     * @param array<string, mixed> $data Metric data
     */
    public function store(string $type, array $data): void;

    /**
     * Store multiple metric points.
     *
     * @param array $metrics Array of metrics
     */
    public function storeBatch(array $metrics): void;

    /**
     * Retrieve metrics for a time period.
     *
     * @param null|string $type Optional filter by type
     */
    public function retrieve(int $fromTimestamp, ?int $toTimestamp = null, ?string $type = null): array;

    /**
     * Clean up old metrics.
     *
     * @return int Number of deleted records
     */
    public function cleanup(int $olderThanTimestamp): int;
}
