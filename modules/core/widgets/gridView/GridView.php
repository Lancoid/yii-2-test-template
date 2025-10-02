<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace app\modules\core\widgets\gridView;

use yii\grid\GridView as YiiGridView;
use yii\helpers\Html;

class GridView extends YiiGridView
{
    public $dataColumnClass = DataColumn::class;

    public $tableOptions = ['class' => 'table dataTable'];

    /**
     * @var bool whether to border grid cells
     */
    public bool $bordered = true;

    /**
     * @var bool whether to condense the grid
     */
    public bool $condensed = false;

    /**
     * @var bool whether to stripe the grid
     */
    public bool $striped = true;

    /**
     * @var bool whether to add a hover for grid rows
     */
    public bool $hover = false;

    public function init(): void
    {
        if ($this->bordered) {
            Html::addCssClass($this->tableOptions, 'table-bordered');
        }

        if ($this->condensed) {
            Html::addCssClass($this->tableOptions, 'table-condensed');
        }

        if ($this->striped) {
            Html::addCssClass($this->tableOptions, 'table-striped');
        }

        if ($this->hover) {
            Html::addCssClass($this->tableOptions, 'table-hover');
        }

        parent::init();
    }

    public function renderPager(): string
    {
        return Html::tag('div', parent::renderPager(), ['class' => 'dataTables_paginate paging_simple_numbers']);
    }
}
