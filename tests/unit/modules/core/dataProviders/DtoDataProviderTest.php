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
        $allModels = [];

        $dtoDataProvider = new DtoDataProvider([
            'allModels' => $allModels,
            'pagination' => [
                'pageSize' => 10,
                'pageSizeParam' => false,
            ],
        ]);

        $dtoDataProvider->setTotalCount(0);
        $dtoDataProvider->getModels();

        $this->assertNotFalse($dtoDataProvider->getPagination());

        $this->assertSame(0, $dtoDataProvider->getPagination()->totalCount);
        $this->assertSame($allModels, $dtoDataProvider->getModels());
    }

    public function testReturnCorrectResult(): void
    {
        $allModels = [
            ['id' => 1],
            ['id' => 2],
            ['id' => 3],
        ];

        $dtoDataProvider = new DtoDataProvider([
            'allModels' => $allModels,
            'pagination' => [
                'pageSize' => 10,
                'pageSizeParam' => false,
            ],
        ]);

        $dtoDataProvider->setTotalCount(count($allModels));
        $dtoDataProvider->getModels();

        $this->assertNotFalse($dtoDataProvider->getPagination());

        $this->assertSame(count($allModels), $dtoDataProvider->getPagination()->totalCount);
        $this->assertSame($allModels, $dtoDataProvider->getModels());
    }
}
