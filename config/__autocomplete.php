<?php

declare(strict_types=1);

use app\modules\user\models\User;
use yii\di\Container;
use yii\rbac\DbManager;
use yii\web\Application;

class Yii
{
    /**
     * @var __Application|Application|yii\console\Application
     */
    public static $app;

    /**
     * @var Container the dependency injection (DI) container used by [[createObject()]]
     */
    public static $container;
}

/**
 * @property DbManager $authManager
 * @property __WebUser|yii\web\User $user
 */
class __Application {}

/**
 * @property User $identity
 */
class __WebUser {}
