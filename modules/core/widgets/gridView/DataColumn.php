<?php

declare(strict_types=1);

namespace app\modules\core\widgets\gridView;

use yii\helpers\Html;

class DataColumn extends \yii\grid\DataColumn
{
    public function renderHeaderCell()
    {
        $provider = $this->grid->dataProvider;

        if (null !== $this->attribute && $this->enableSorting
            && ($sort = $provider->getSort()) !== false && $sort->hasAttribute($this->attribute)) {
            if (($direction = $sort->getAttributeOrder($this->attribute)) !== null) {
                Html::addCssClass($this->headerOptions, 'sorting_' . (SORT_DESC === $direction ? 'desc' : 'asc'));
            } else {
                Html::addCssClass($this->headerOptions, 'sorting');
            }
        }

        return Html::tag('th', $this->renderHeaderCellContent(), $this->headerOptions);
    }
}
