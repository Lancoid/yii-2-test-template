<?php

declare(strict_types=1);

namespace app\modules\core\widgets\gridView;

use yii\grid\GridView as YiiGridView;
use yii\helpers\Html;

/**
 * Extended GridView widget with additional Bootstrap table styling options.
 *
 * This widget allows easy configuration of bordered, condensed, striped, and hover table styles
 * for enhanced visual presentation. It also wraps the pager in a custom styled container.
 *
 * @property string $dataColumnClass Data column class name.
 * @property array<string, mixed> $tableOptions HTML attributes for the table tag.
 * @property bool $bordered Whether to border grid cells.
 * @property bool $condensed Whether to condense the grid.
 * @property bool $striped Whether to stripe the grid.
 * @property bool $hover Whether to add a hover effect for grid rows.
 */
class GridView extends YiiGridView
{
    /** @var string Data column class name. */
    public $dataColumnClass = DataColumn::class;

    /** @var array<string, mixed> HTML attributes for the table tag. */
    public $tableOptions = ['class' => self::BASE_TABLE_CLASS];

    /** @var bool Whether to border grid cells. */
    public bool $bordered = true;

    /** @var bool Whether to condense the grid. */
    public bool $condensed = false;

    /** @var bool Whether to stripe the grid. */
    public bool $striped = true;

    /** @var bool Whether to add a hover effect for grid rows. */
    public bool $hover = false;

    /** @var string Base table CSS classes. */
    private const string BASE_TABLE_CLASS = 'table dataTable';

    /** @var string CSS class for bordered tables. */
    private const string BORDERED_CLASS = 'table-bordered';

    /** @var string CSS class for condensed tables. */
    private const string CONDENSED_CLASS = 'table-condensed';

    /** @var string CSS class for striped tables. */
    private const string STRIPED_CLASS = 'table-striped';

    /** @var string CSS class for hover effect. */
    private const string HOVER_CLASS = 'table-hover';

    /** @var string CSS class for the pager container. */
    private const string PAGER_CLASS = 'dataTables_paginate paging_simple_numbers';

    /**
     * Initializes the widget and applies table CSS classes based on properties.
     *
     * Adds appropriate Bootstrap classes to the table depending on the configuration.
     */
    public function init(): void
    {
        if ($this->bordered && is_array($this->tableOptions)) {
            Html::addCssClass($this->tableOptions, self::BORDERED_CLASS);
        }

        if ($this->condensed && is_array($this->tableOptions)) {
            Html::addCssClass($this->tableOptions, self::CONDENSED_CLASS);
        }

        if ($this->striped && is_array($this->tableOptions)) {
            Html::addCssClass($this->tableOptions, self::STRIPED_CLASS);
        }

        if ($this->hover && is_array($this->tableOptions)) {
            Html::addCssClass($this->tableOptions, self::HOVER_CLASS);
        }

        parent::init();
    }

    /**
     * Renders the pager wrapped in a div with custom CSS classes.
     *
     * @return string HTML code for the pager
     */
    public function renderPager(): string
    {
        return Html::tag(
            'div',
            parent::renderPager(),
            ['class' => self::PAGER_CLASS]
        );
    }
}
