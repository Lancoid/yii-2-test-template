<?php

declare(strict_types=1);

namespace app\modules\user\controllers;

use app\modules\core\services\exceptions\ServiceFormValidationException;
use app\modules\core\services\sentry\SentryServiceInterface;
use app\modules\user\forms\UserLoginForm;
use app\modules\user\services\login\UserLoginServiceInterface;
use Throwable;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;
use yii\web\Session;

class AuthorizationController extends Controller
{
    /**
     * @return array<string, mixed>
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['logout'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionLogin(
        Request $request,
        Response $response,
        UserLoginServiceInterface $userLoginService,
        Session $session,
        SentryServiceInterface $sentryService,
    ): array|Response|string {
        $userLoginForm = new UserLoginForm();

        if ($request->isAjax && $userLoginForm->load($request->post())) {
            $response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($userLoginForm);
        }

        if ($userLoginForm->load($request->post()) && $userLoginForm->validate()) {
            $result = false;

            try {
                $result = $userLoginService->handle($userLoginForm);
            } catch (ServiceFormValidationException $exception) {
                $userLoginForm->addError($exception->getAttribute() ?? '', $exception->getErrorMessage() ?? '');
            } catch (Throwable $throwable) {
                $session->addFlash('error', $throwable->getMessage());

                $sentryService->captureException($throwable);
            }

            if ($result) {
                return $this->goHome();
            }
        }

        $userLoginForm->password = '';

        return $this->render('login', ['loginForm' => $userLoginForm]);
    }

    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
