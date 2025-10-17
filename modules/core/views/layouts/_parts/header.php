<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use app\modules\core\CoreModule;
use app\modules\user\models\User as AppWebUser;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\web\IdentityInterface;
use yii\web\User as YiiWebUser;

/** @var ?YiiWebUser<IdentityInterface> $yiiUser */
$yiiUser = Yii::$app->user;
/** @var ?AppWebUser $appUser */
$appUser = $yiiUser?->identity;

?>

<header id="header">
<?php NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top'],
]); ?>

<?php

$menuItems = [
    [
        'label' => CoreModule::t('menu', 'Login'),
        'url' => ['/login'],
        'visible' => $yiiUser?->isGuest,
    ],
    [
        'label' => $appUser?->username,
        'url' => '#',
        'items' => [
            [
                'label' => CoreModule::t('menu', 'Logout'),
                'url' => ['/logout'],
                'linkOptions' => ['data-method' => 'post'],
            ],
        ],
        'visible' => !$yiiUser?->isGuest,
    ],
];

?>

<?= Nav::widget([
    'options' => ['class' => 'navbar-nav ms-auto mb-2 mb-lg-0'],
    'items' => $menuItems,
]); ?>
<?php NavBar::end() ?>
</header>
