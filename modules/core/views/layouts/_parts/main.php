<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use app\modules\core\widgets\flashAlert\FlashAlert;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/**
 * @var View $this
 * @var string $content
 */
?>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])) { ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php } ?>
        <?= FlashAlert::widget() ?>
        <?= $content ?>
    </div>
</main>