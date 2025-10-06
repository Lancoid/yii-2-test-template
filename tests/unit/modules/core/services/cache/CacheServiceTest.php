<?php

declare(strict_types=1);

namespace app\tests\unit\modules\core\services\cache;

use app\modules\core\dictionaries\TimeDurationDictionary;
use app\modules\core\services\cache\CacheServiceInterface;
use Codeception\Test\Unit;
use Yii;

/**
 * @internal
 *
 * @covers \app\modules\core\services\cache\CacheService
 */
final class CacheServiceTest extends Unit
{
    public ?CacheServiceInterface $cacheService = null;

    protected function _before(): void
    {
        /** @var CacheServiceInterface $cacheService */
        $cacheService = Yii::$container->get(CacheServiceInterface::class);

        $this->assertInstanceOf(CacheServiceInterface::class, $cacheService);

        $this->cacheService = $cacheService;
    }

    public function testSetGetDelete(): void
    {
        if (!$this->cacheService instanceof CacheServiceInterface) {
            $this->fail('Cache service is not initialized');
        }

        $key = 'cache_service_test:key1';
        $value = ['a' => 1, 'b' => 2];

        $this->assertTrue($this->cacheService->set($key, $value));
        $this->assertSame($value, $this->cacheService->get($key));

        $this->assertTrue($this->cacheService->delete($key));
        $this->assertFalse($this->cacheService->get($key));
    }

    public function testOverwriteExistingKey(): void
    {
        if (!$this->cacheService instanceof CacheServiceInterface) {
            $this->fail('Cache service is not initialized');
        }

        $key = 'cache_service_test:key2';

        $this->assertTrue($this->cacheService->set($key, 'old'));
        $this->assertTrue($this->cacheService->set($key, 'new'));

        $this->assertSame('new', $this->cacheService->get($key));
    }

    public function testTtlExpiration(): void
    {
        if (!$this->cacheService instanceof CacheServiceInterface) {
            $this->fail('Cache service is not initialized');
        }

        $key = 'cache_service_test:key3';
        $value = 123;

        $this->assertTrue($this->cacheService->set($key, $value, TimeDurationDictionary::ONE_SECOND));
        $this->assertSame($value, $this->cacheService->get($key));

        // wait for expiration
        sleep(TimeDurationDictionary::ONE_SECOND + 1);

        $this->assertFalse($this->cacheService->get($key));
    }
}
