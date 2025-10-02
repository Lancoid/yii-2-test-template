<?php

declare(strict_types=1);

use app\modules\user\models\User;
use yii\rbac\DbManager;
use yii\web\Application;

class Yii
{
    /**
     * @var __Application|Application|yii\console\Application
     */
    public static $app;
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
