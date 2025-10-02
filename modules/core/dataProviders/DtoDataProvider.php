<?php

declare(strict_types=1);

namespace app\modules\core\dataProviders;

use yii\data\ArrayDataProvider;

class DtoDataProvider extends ArrayDataProvider
{
    protected function prepareModels(): array
    {
        if (null === $this->allModels) {
            return [];
        }

        if (($pagination = $this->getPagination()) !== false) {
            $pagination->totalCount = $this->getTotalCount();
        }

        return $this->allModels;
    }
}
