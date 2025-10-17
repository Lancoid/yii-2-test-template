<?php

declare(strict_types=1);

namespace app\modules\user\controllers;

use app\modules\core\services\exceptions\ServiceFormValidationException;
use app\modules\core\services\sentry\SentryServiceInterface;
use app\modules\user\forms\UserRegistrationForm;
use app\modules\user\services\create\UserCreateServiceInterface;
use Throwable;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;
use yii\web\Session;

/**
 * RegistrationController handles user registration actions.
 *
 * Provides registration form display, validation, and user creation logic.
 */
class RegistrationController extends Controller
{
    /**
     * Configures access control for the controller.
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
                        'actions' => ['index'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Handles user registration form display, validation, and user creation.
     *
     * Supports AJAX validation and error reporting.
     *
     * @param Request $request HTTP request object
     * @param Response $response HTTP response object
     * @param UserCreateServiceInterface $userCreateService service for user creation
     * @param Session $session yii session object
     * @param SentryServiceInterface $sentryService service for error reporting
     *
     * @return array|Response|string the result of registration action
     */
    public function actionIndex(
        Request $request,
        Response $response,
        UserCreateServiceInterface $userCreateService,
        Session $session,
        SentryServiceInterface $sentryService,
    ): array|Response|string {
        $userRegistrationForm = new UserRegistrationForm();

        if ($request->isAjax && $userRegistrationForm->load($request->post())) {
            $response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($userRegistrationForm);
        }

        if ($userRegistrationForm->load($request->post()) && $userRegistrationForm->validate()) {
            $result = false;

            try {
                $result = $userCreateService->handle($userRegistrationForm);
            } catch (ServiceFormValidationException $exception) {
                $userRegistrationForm->addError($exception->getAttribute(), $exception->getErrorMessage());
            } catch (Throwable $throwable) {
                $session->addFlash('error', $throwable->getMessage());
                $sentryService->captureException($throwable);
            }

            if ($result) {
                return $this->goHome();
            }
        }

        $userRegistrationForm->password = '';

        return $this->render('index', ['registrationForm' => $userRegistrationForm]);
    }
}
