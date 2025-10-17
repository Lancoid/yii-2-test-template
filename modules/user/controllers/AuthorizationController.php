<?php

declare(strict_types=1);

namespace app\modules\user\controllers;

use app\modules\core\services\audit\AuditLogServiceInterface;
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

/**
 * AuthorizationController handles user authentication actions.
 *
 * Provides login and logout endpoints with access control and error handling.
 */
class AuthorizationController extends Controller
{
    /**
     * Configures access control and HTTP verb filters for the controller.
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

    /**
     * Handles user login form display, validation, and authentication.
     *
     * Supports AJAX validation and error reporting.
     *
     * @param Request $request HTTP request object
     * @param Response $response HTTP response object
     * @param UserLoginServiceInterface $userLoginService service for user authentication
     * @param Session $session yii session object
     * @param SentryServiceInterface $sentryService service for error reporting
     *
     * @return array|Response|string the result of login action
     *
     * @throws Throwable
     */
    public function actionLogin(
        Request $request,
        Response $response,
        UserLoginServiceInterface $userLoginService,
        Session $session,
        SentryServiceInterface $sentryService,
        AuditLogServiceInterface $auditLogService,
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
                $userLoginForm->addError($exception->getAttribute(), $exception->getErrorMessage());
            } catch (Throwable $throwable) {
                $session->addFlash('error', $throwable->getMessage());
                $sentryService->captureException($throwable);
            }

            $auditLogService->logAuth($userLoginForm->getEmail() ?? '', $result);

            if ($result) {
                return $this->goHome();
            }
        }

        $userLoginForm->password = '';

        return $this->render('login', ['loginForm' => $userLoginForm]);
    }

    /**
     * Logs out the current user and redirects to the home page.
     *
     * @return Response redirect response to home
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
