<?php

declare(strict_types=1);

namespace app\tests\unit\modules\core\dataProviders;

use app\modules\core\dataProviders\DtoDataProvider;
use Codeception\Test\Unit;

/**
 * @internal
 *
 * @coversNothing
 */
final class DtoDataProviderTest extends Unit
{
    public function testReturnsEmptyArray(): void
    {
        $provider = new DtoDataProvider();

        $this->assertSame([], $provider->getModels());
    }

    public function testReturnCorrectResult(): void
    {
        $allModels = [
            ['id' => 1],
            ['id' => 2],
            ['id' => 3],
        ];

        $provider = new DtoDataProvider([
            'allModels' => $allModels,
            'pagination' => [
                'pageSize' => 10,
                'pageSizeParam' => false,
            ],
        ]);

        $provider->setTotalCount(count($allModels));
        $provider->getModels();

        $this->assertSame(3, $provider->getPagination()->totalCount);
        $this->assertSame($allModels, $provider->getModels());
    }
}
