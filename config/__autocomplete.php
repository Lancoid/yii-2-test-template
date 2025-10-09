<?php

declare(strict_types=1);

use yii\di\Container;
use yii\rbac\DbManager;
use yii\web\Application;
use yii\web\Request;
use yii\web\Response;
use yii\web\User;

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
     * @var User the user component
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
