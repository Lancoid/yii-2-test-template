<?php

declare(strict_types=1);

namespace app\modules\core\services\cache;

use yii\caching\CacheInterface;

readonly class CacheService implements CacheServiceInterface
{
    public function __construct(
        private CacheInterface $cache,
    ) {}

    public function set(int|string $key, mixed $value, ?int $duration = null): bool
    {
        return $this->cache->set($key, $value, $duration);
    }

    public function get(int|string $key): mixed
    {
        return $this->cache->get($key);
    }

    public function delete(int|string $key): bool
    {
        return $this->cache->delete($key);
    }
}
