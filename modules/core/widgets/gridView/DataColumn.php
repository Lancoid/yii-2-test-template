<?php

declare(strict_types=1);

namespace app\modules\core\widgets\gridView;

use yii\grid\DataColumn as YiiDataColumn;
use yii\helpers\Html;

/**
 * Extended data column for GridView with sorting support and custom CSS classes.
 *
 * This class adds sorting CSS classes to the header cell based on the current sort direction.
 */
class DataColumn extends YiiDataColumn
{
    /**
     * CSS class for sortable column header.
     */
    private const string SORTING_CLASS = 'sorting';

    /**
     * Renders the header cell with sorting CSS classes if applicable.
     *
     * Adds a CSS class indicating the sort direction (`sorting_asc`, `sorting_desc`)
     * or a generic `sorting` class if the column is sortable but not currently sorted.
     *
     * @return string HTML code for the header cell
     */
    public function renderHeaderCell(): string
    {
        $provider = $this->grid->dataProvider;

        if (
            null !== $this->attribute
            && $this->enableSorting
            && ($sort = $provider->getSort()) !== false
            && $sort->hasAttribute($this->attribute)
        ) {
            if (($direction = $sort->getAttributeOrder($this->attribute)) !== null) {
                Html::addCssClass(
                    $this->headerOptions,
                    self::SORTING_CLASS . '_' . (SORT_DESC === $direction ? 'desc' : 'asc')
                );
            } else {
                Html::addCssClass($this->headerOptions, self::SORTING_CLASS);
            }
        }

        return Html::tag('th', $this->renderHeaderCellContent(), $this->headerOptions);
    }
}
