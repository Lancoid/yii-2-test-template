<?php

declare(strict_types=1);

namespace app\modules\core\controllers;

use juliardi\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ErrorAction;

/**
 * DefaultController handles core module actions such as index, captcha, and error.
 *
 * Provides access control and custom actions for the module.
 */
class DefaultController extends Controller
{
    /**
     * Returns a list of behaviors for this controller.
     *
     * @return array<string, mixed> the behaviors configuration
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'captcha',
                            'error',
                            'index',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Declares external actions for the controller.
     *
     * @return array<string, array<string, mixed>> the actions configuration
     */
    public function actions(): array
    {
        return [
            'captcha' => [
                'class' => CaptchaAction::class,
                'length' => 5,
                'quality' => 100,
            ],
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * Renders the index page.
     *
     * @return string the rendering result
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }
}
