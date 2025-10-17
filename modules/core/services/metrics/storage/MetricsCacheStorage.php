<?php

declare(strict_types=1);

namespace app\modules\core\services\metrics\storage;

use app\modules\core\dictionaries\TimeDurationDictionary;
use app\modules\core\services\cache\CacheServiceInterface;
use Random\RandomException;

/**
 * Cache-based metrics storage implementation.
 *
 * Stores metrics in cache with automatic expiration.
 * Suitable for high-performance scenarios with short-term metrics retention.
 */
readonly class MetricsCacheStorage implements MetricsStorageInterface
{
    private const string METRICS_KEY_PREFIX = 'metrics:';
    private const string METRICS_INDEX_KEY = 'metrics:index';
    private const int DEFAULT_TTL = TimeDurationDictionary::ONE_HOUR;

    public function __construct(
        private CacheServiceInterface $cacheService,
        private int $ttl = self::DEFAULT_TTL
    ) {}

    /**
     * @throws RandomException
     */
    public function store(string $type, array $data): void
    {
        /** @var int $timestamp */
        $timestamp = $data['timestamp'] ?? $this->now();
        $key = $this->generateMetricKey($type, $timestamp);

        $data['timestamp'] = $timestamp;
        $data['type'] = $type;

        $this->cacheService->set($key, $data, $this->ttl);
        $this->addToIndex($key, $type, $timestamp);
    }

    /**
     * @throws RandomException
     */
    public function storeBatch(array $metrics): void
    {
        $keyValuePairs = [];
        $indexUpdates = [];

        foreach ($metrics as $metric) {
            $type = $metric['type'];
            $timestamp = $metric['timestamp'];

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
        $toTimestamp ??= $this->now();
        $index = $this->getIndex();

        $filteredKeys = array_column(
            array_filter($index, fn (array $entry): bool => $entry['timestamp'] >= $fromTimestamp
                && $entry['timestamp'] <= $toTimestamp
                && (null === $type || $entry['type'] === $type)
            ),
            'key'
        );

        if (empty($filteredKeys)) {
            return [];
        }

        /** @var array<int, array<string, mixed>> $metrics */
        $metrics = $this->cacheService->multiGet($filteredKeys);

        return array_values(array_filter($metrics, fn (array $metric): bool => false !== $metric));
    }

    public function cleanup(int $olderThanTimestamp): int
    {
        $index = $this->getIndex();
        $deletedCount = 0;

        $newIndex = array_filter($index, function (array $entry) use (&$deletedCount, $olderThanTimestamp): bool {
            if ($entry['timestamp'] < $olderThanTimestamp) {
                $this->cacheService->delete($entry['key']);
                ++$deletedCount;

                return false;
            }

            return true;
        });

        $this->cacheService->set(self::METRICS_INDEX_KEY, array_values($newIndex), $this->ttl);

        return $deletedCount;
    }

    /**
     * @throws RandomException
     */
    private function generateMetricKey(string $type, int $timestamp): string
    {
        $uniqueId = bin2hex(random_bytes(8));

        return self::METRICS_KEY_PREFIX . sprintf('%d_%s_%s', $timestamp, $type, $uniqueId);
    }

    private function addToIndex(string $key, string $type, int $timestamp): void
    {
        $index = $this->getIndex();

        $index[] = [
            'key' => $key,
            'type' => $type,
            'timestamp' => $timestamp,
        ];

        $index = $this->sortIndex($index);
        $this->cacheService->set(self::METRICS_INDEX_KEY, $index, $this->ttl);
    }

    /**
     * @return array<array{key: string, type: string, timestamp: int}>
     */
    private function getIndex(): array
    {
        /** @var array<array{key: string, type: string, timestamp: int}> $index */
        $index = $this->cacheService->get(self::METRICS_INDEX_KEY);

        return is_array($index) ? $index : [];
    }

    /**
     * @param array<array{key: string, type: string, timestamp: int}> $index
     *
     * @return array<array{key: string, type: string, timestamp: int}>
     */
    private function sortIndex(array $index): array
    {
        if (empty($index)) {
            return [];
        }
        usort($index, fn (array $a, array $b): int => $a['timestamp'] <=> $b['timestamp']);

        return $index;
    }

    private function now(): int
    {
        return time();
    }
}
