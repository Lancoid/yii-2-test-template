<?php

declare(strict_types=1);

namespace app\modules\core\services\metrics\storage;

/**
 * Interface for metrics storage implementations.
 * Provides methods to store, batch store, retrieve, and clean up metric data.
 */
interface MetricsStorageInterface
{
    /**
     * Store a single metric point.
     *
     * @param string $type Metric type (e.g., 'request', 'error', 'counter', 'gauge')
     * @param array<string, mixed> $data Metric data
     */
    public function store(string $type, array $data): void;

    /**
     * Store multiple metric points in batch.
     *
     * @param array<int, array{
     *     type: string,
     *     timestamp: int,
     *     data: array<string, mixed>
     *         }
     *     > $metrics Array of metrics
     */
    public function storeBatch(array $metrics): void;

    /**
     * Retrieve metrics for a given time period and optional type.
     *
     * @param int $fromTimestamp Start timestamp (Unix time)
     * @param null|int $toTimestamp End timestamp (null for now)
     * @param null|string $type Optional filter by metric type
     *
     * @return array<int, array<string, mixed>> Array of metric data
     */
    public function retrieve(int $fromTimestamp, ?int $toTimestamp = null, ?string $type = null): array;

    /**
     * Clean up metrics older than the specified timestamp.
     *
     * @param int $olderThanTimestamp Metrics older than this timestamp will be deleted
     *
     * @return int Number of deleted records
     */
    public function cleanup(int $olderThanTimestamp): int;
}
