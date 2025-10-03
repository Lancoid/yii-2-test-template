<?php

declare(strict_types=1);

namespace app\modules\track\controllers;

use app\modules\core\dictionaries\HttpCodeDictionary;
use app\modules\core\services\exceptions\ServiceFormValidationException;
use app\modules\core\services\logger\LoggerFileServiceInterface;
use app\modules\track\components\errorHandler\ApiErrorHandler;
use app\modules\track\dataProviders\TrackDataProviderInterface;
use app\modules\track\forms\TrackCreateForm;
use app\modules\track\forms\TrackUpdateForm;
use app\modules\track\services\create\TrackCreateServiceInterface;
use app\modules\track\services\delete\TrackDeleteServiceInterface;
use app\modules\track\services\update\TrackUpdateServiceInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;

class ApiController extends Controller
{
    public function behaviors(): array
    {
        return [
            'auth' => [
                'class' => HttpBearerAuth::class,
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post'],
                    'delete' => ['delete'],
                    'update' => ['put'],
                    'view' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        Yii::$app->user->enableSession = false;

        Yii::$app->errorHandler->unregister();
        Yii::$app->set('errorHandler', ApiErrorHandler::class);
        Yii::$app->errorHandler->register();

        /** @var Request $request */
        $request = Yii::$app->request;
        $request->enableCsrfValidation = false;
        $request->enableCsrfCookie = false;

        /** @var Response $response */
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        $response->on(Response::EVENT_BEFORE_SEND, function () use ($request, $response): void {
            /** @var LoggerFileServiceInterface $loggerFileService */
            $loggerFileService = Yii::$container->get(LoggerFileServiceInterface::class);

            $loggerFileService->info([
                'request' => [
                    'data' => $request->post() ?? $request->getBodyParams(),
                    'headers' => $request->getHeaders()->toArray(),
                    'url' => $request->absoluteUrl,
                ],
                'response' => [
                    'code' => $response->statusCode,
                    'data' => $response->data,
                ],
            ], 'track_api');
        });
    }

    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    public function actionCreate(
        TrackCreateServiceInterface $trackCreateService,
        Request $request,
    ): array {
        $trackCreateForm = new TrackCreateForm();
        $trackCreateForm->load($request->post(), '');

        if (!$trackCreateForm->validate()) {
            foreach ($trackCreateForm->getErrors() as $attribute => $errors) {
                throw new ServiceFormValidationException($attribute, $errors[0], HttpCodeDictionary::BAD_REQUEST);
            }
        }

        $id = $trackCreateService->handle($trackCreateForm);

        return ['result' => ['id' => $id]];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionDelete(
        int $id,
        TrackDataProviderInterface $trackDataProvider,
        TrackDeleteServiceInterface $trackDeleteService
    ): array {
        $dto = $trackDataProvider->getOne($id);

        if (!$dto) {
            throw new NotFoundHttpException('Not found', HttpCodeDictionary::NOT_FOUND);
        }

        return ['result' => ['is_deleted' => $trackDeleteService->handle($id)]];
    }

    /**
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionUpdate(
        int $id,
        TrackDataProviderInterface $trackDataProvider,
        TrackUpdateServiceInterface $trackUpdateService,
        Request $request,
    ): array {
        $trackDto = $trackDataProvider->getOne($id);

        if (!$trackDto) {
            throw new NotFoundHttpException('Not found', HttpCodeDictionary::NOT_FOUND);
        }

        $trackUpdateForm = new TrackUpdateForm($trackDto);
        $trackUpdateForm->load((array)$request->getBodyParams(), '');

        if (!$trackUpdateForm->validate()) {
            foreach ($trackUpdateForm->getErrors() as $attribute => $errors) {
                throw new ServiceFormValidationException($attribute, $errors[0], HttpCodeDictionary::BAD_REQUEST);
            }
        }

        $id = $trackUpdateService->handle($trackUpdateForm);

        return ['result' => ['id' => $id]];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(
        int $id,
        TrackDataProviderInterface $trackDataProvider
    ): array {
        $dto = $trackDataProvider->getOne($id);

        if (!$dto) {
            throw new NotFoundHttpException('Not found', HttpCodeDictionary::NOT_FOUND);
        }

        return ['result' => ['data' => $dto->getData()]];
    }
}
