<?php

declare(strict_types=1);

namespace app\modules\core\services\cache;

interface CacheServiceInterface
{
    /**
     * @description Stores a value identified by a key into cache.
     * If the cache already contains such a key, the existing value and
     * expiration time will be replaced with the new ones, respectively.
     *
     * @param int|string $key a key identifying the value to be cached. This can be a simple string or
     *                        a complex data structure consisting of factors representing the key.
     * @param mixed $value the value to be cached
     * @param null|int $duration default duration in seconds before the cache will expire.
     *                           If not set, default [[defaultDuration]] value is used.
     */
    public function set(int|string $key, mixed $value, ?int $duration = null): bool;

    /**
     * @description Retrieves a value from cache with a specified key.
     *
     * @param int|string $key a key identifying the cached value. This can be a simple string or
     *                        a complex data structure consisting of factors representing the key.
     *
     * @return mixed the value stored in cache, false if the value is not in the cache, expired,
     *               or the dependency associated with the cached data has changed
     */
    public function get(int|string $key): mixed;

    /**
     * @description Deletes a value with the specified key from cache.
     *
     * @param int|string $key a key identifying the value to be deleted from cache. This can be a simple string or
     *                        a complex data structure consisting of factors representing the key.
     *
     * @return bool if no error happens during deletion
     */
    public function delete(int|string $key): bool;

    /**
     * @description Stores multiple items in cache. Each item contains a value identified by a key.
     * If the cache already contains such a key, the existing value and expiration time will be replaced with the new ones, respectively.
     *
     * @param array $items the items to be cached, as key-value pairs
     * @param null|int $duration default duration in seconds before the cache will expire.
     *                           If not set, default [[defaultDuration]] value is used.
     *
     * @return array array of failed keys
     */
    public function multiSet(array $items, ?int $duration = null): array;

    /**
     * @description  Retrieves multiple values from cache with the specified keys.
     * Some caches (such as memcache, apc) allow retrieving multiple cached values at the same time,
     * which may improve the performance. In case a cache does not support this feature natively,
     * this method will try to simulate it.
     *
     * @param array<string> $keys list of string keys identifying the cached values
     *
     * @return array list of cached values corresponding to the specified keys. The array
     *               is returned in terms of (key, value) pairs.
     *               If a value is not cached or expired, the corresponding array value will be false.
     */
    public function multiGet(array $keys): array;
}
