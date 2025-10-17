<?php

declare(strict_types=1);

namespace app\modules\core\dataProviders;

use yii\data\ArrayDataProvider;

/**
 * Data provider for DTO collections.
 *
 * Extends ArrayDataProvider to support custom model preparation logic.
 */
class DtoDataProvider extends ArrayDataProvider
{
    /**
     * Prepares the list of models for data provider.
     *
     * @return array the list of models
     */
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
