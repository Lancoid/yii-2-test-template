<?php

declare(strict_types=1);

namespace app\modules\core\services\metrics\storage;

use app\modules\core\dictionaries\TimeDurationDictionary;
use app\modules\core\services\cache\CacheServiceInterface;

/**
 * Cache-based metrics storage implementation.
 *
 * Stores metrics in cache with automatic expiration.
 * Suitable for high-performance scenarios with short-term metrics retention.
 */
readonly class CacheMetricsStorage implements MetricsStorageInterface
{
    private const string METRICS_KEY_PREFIX = 'metrics:';
    private const string METRICS_INDEX_KEY = 'metrics:index';
    private const int DEFAULT_TTL = TimeDurationDictionary::ONE_HOUR;

    public function __construct(
        private CacheServiceInterface $cacheService,
        private int $ttl = self::DEFAULT_TTL) {}

    public function store(string $type, array $data): void
    {
        /** @var int $timestamp */
        $timestamp = $data['timestamp'] ?? time();
        $key = $this->generateMetricKey($type, $timestamp);

        $data['timestamp'] = $timestamp;
        $data['type'] = $type;

        // Store the metric
        $this->cacheService->set($key, $data, $this->ttl);

        // Add to index for retrieval
        $this->addToIndex($key, $type, $timestamp);
    }

    public function storeBatch(array $metrics): void
    {
        $keyValuePairs = [];
        $indexUpdates = [];

        foreach ($metrics as $metric) {
            /** @var string $type */
            $type = $metric['type'] ?? 'unknown';
            /** @var int<1, max> $timestamp */
            $timestamp = $metric['timestamp'] ?? time();

            $key = $this->generateMetricKey($type, $timestamp);

            $metric['timestamp'] = $timestamp;
            $metric['type'] = $type;

            $keyValuePairs[$key] = $metric;

            $indexUpdates[] = [
                'key' => $key,
                'type' => $type,
                'timestamp' => $timestamp,
            ];
        }

        // Store all metrics
        $this->cacheService->multiSet($keyValuePairs, $this->ttl);

        // Update index
        foreach ($indexUpdates as $indexUpdate) {
            $this->addToIndex($indexUpdate['key'], $indexUpdate['type'], $indexUpdate['timestamp']);
        }
    }

    public function retrieve(int $fromTimestamp, ?int $toTimestamp = null, ?string $type = null): array
    {
        $toTimestamp ??= time();

        // Get index
        $index = $this->getIndex();

        // Filter by time range and type
        $filteredKeys = [];
        foreach ($index as $entry) {
            if ($entry['timestamp'] < $fromTimestamp) {
                continue;
            }
            if ($entry['timestamp'] > $toTimestamp) {
                continue;
            }
            if (null !== $type && $entry['type'] !== $type) {
                continue;
            }

            $filteredKeys[] = $entry['key'];
        }

        if (empty($filteredKeys)) {
            return [];
        }

        // Retrieve metrics
        $metrics = $this->cacheService->multiGet($filteredKeys);

        // Filter out false values (expired or deleted metrics)
        return array_values(array_filter($metrics, fn ($metric): bool => false !== $metric));
    }

    public function cleanup(int $olderThanTimestamp): int
    {
        $index = $this->getIndex();
        $deletedCount = 0;
        $newIndex = [];

        foreach ($index as $entry) {
            if ($entry['timestamp'] < $olderThanTimestamp) {
                // Delete the metric
                $this->cacheService->delete($entry['key']);
                ++$deletedCount;
            } else {
                $newIndex[] = $entry;
            }
        }

        // Update index
        $this->cacheService->set(self::METRICS_INDEX_KEY, $newIndex, $this->ttl);

        return $deletedCount;
    }

    /**
     * Generate a unique cache key for a metric.
     */
    private function generateMetricKey(string $type, int $timestamp): string
    {
        $microtime = microtime(true);
        $uniqueId = sprintf('%d_%s_%s', $timestamp, $type, str_replace('.', '', (string)$microtime));

        return self::METRICS_KEY_PREFIX . $uniqueId;
    }

    /**
     * Add metrics key to the index.
     */
    private function addToIndex(string $key, string $type, int $timestamp): void
    {
        $index = $this->getIndex();

        $index[] = [
            'key' => $key,
            'type' => $type,
            'timestamp' => $timestamp,
        ];

        // Keep index sorted by timestamp for efficient cleanup
        usort($index, fn (array $a, array $b): int => $a['timestamp'] <=> $b['timestamp']);

        $this->cacheService->set(self::METRICS_INDEX_KEY, $index, $this->ttl);
    }

    /**
     * Get the metrics index.
     *
     * @return array<array{key: string, type: string, timestamp: int}>
     */
    private function getIndex(): array
    {
        /** @var array<array{key: string, type: string, timestamp: int}> $index */
        $index = $this->cacheService->get(self::METRICS_INDEX_KEY);

        return is_array($index) ? $index : [];
    }
}
