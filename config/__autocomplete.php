<?php

declare(strict_types=1);

use yii\di\Container;
use yii\rbac\DbManager;
use yii\web\Application;
use yii\web\Request;
use yii\web\Response;
use yii\web\User;

/*
 * This constant defines whether the application should be in debug mode or not. Defaults to false.
 */
defined('YII_DEBUG') || define('YII_DEBUG', false);
/*
 * This constant defines in which environment the application is running. Defaults to 'prod', meaning production environment.
 * You may define this constant in the bootstrap script. The value could be 'prod' (production), 'dev' (development), 'test', 'staging', etc.
 */
defined('YII_ENV') || define('YII_ENV', 'prod');
/*
 * Whether the application is running in the production environment.
 */
defined('YII_ENV_PROD') || define('YII_ENV_PROD', YII_ENV === 'prod');
/*
 * Whether the application is running in the development environment.
 */
defined('YII_ENV_DEV') || define('YII_ENV_DEV', YII_ENV === 'dev');
/*
 * Whether the application is running in the testing environment.
 */
defined('YII_ENV_TEST') || define('YII_ENV_TEST', YII_ENV === 'test');
class Yii
{
    /**
     * @var __Application|Application the application instance (equivalent to `Yii::$app` in Yii 2)
     */
    public static $app;

    /**
     * @var Container the dependency injection (DI) container used by [[createObject()]]
     */
    public static $container;
}

class __Application
{
    /**
     * @var DbManager the authentication manager
     */
    public $authManager;

    /**
     * @var User<app\modules\user\models\User> the user component
     */
    public $user;

    /**
     * @var Request the request component
     */
    public $request;

    /**
     * @var Response the response component
     */
    public $response;

    /**
     * @var string the route of the current request
     */
    public $requestedRoute;
}
