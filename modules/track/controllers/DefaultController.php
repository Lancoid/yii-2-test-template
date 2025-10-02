<?php

declare(strict_types=1);

namespace app\modules\track\controllers;

use app\modules\core\dataProviders\DtoDataProvider;
use app\modules\track\dataProviders\dto\TrackDataProviderListViewDto;
use app\modules\track\dataProviders\TrackDataProviderInterface;
use app\modules\track\forms\TrackCreateForm;
use app\modules\track\forms\TrackSearchForm;
use app\modules\track\forms\TrackUpdateForm;
use app\modules\track\services\create\TrackCreateServiceInterface;
use app\modules\track\services\delete\TrackDeleteServiceInterface;
use app\modules\track\services\update\TrackUpdateServiceInterface;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\Session;

class DefaultController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        //                        'roles' => [UserPermissionDictionary::ROLE_USER],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(
        Request $request,
        TrackDataProviderInterface $trackDataProvider
    ): string {
        $trackSearchForm = new TrackSearchForm();
        $trackSearchForm->load($request->queryParams);

        $listViewDto = new TrackDataProviderListViewDto();

        if ($trackSearchForm->validate()) {
            $listViewDto = $trackDataProvider->getListView($trackSearchForm);
        }

        $dtoDataProvider = new DtoDataProvider([
            'allModels' => $listViewDto->getData(),
            'pagination' => [
                'pageSize' => 10,
                'pageSizeParam' => false,
            ],
        ]);

        $dtoDataProvider->setTotalCount($listViewDto->getTotalCount());

        return $this->render('index', [
            'searchModel' => $trackSearchForm,
            'dataProvider' => $dtoDataProvider,
        ]);
    }

    public function actionCreate(
        Request $request,
        Response $response,
        Session $session,
        TrackCreateServiceInterface $trackCreateService,
    ): array|Response|string {
        $trackCreateForm = new TrackCreateForm();

        if ($request->isAjax && $trackCreateForm->load($request->post())) {
            $response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($trackCreateForm);
        }

        if ($request->isPost && $trackCreateForm->load($request->post()) && $trackCreateForm->validate()) {
            $id = $trackCreateService->handle($trackCreateForm);

            if (0 !== $id) {
                $session->setFlash('success', 'Трек успешно добавлен');

                return $this->redirect(['view', 'id' => $id]);
            }

            $session->setFlash('error', 'Не удалось создать трек');
        }

        return $this->render('create', ['createForm' => $trackCreateForm]);
    }

    public function actionUpdate(
        int $id,
        Request $request,
        Response $response,
        Session $session,
        TrackDataProviderInterface $trackDataProvider,
        TrackUpdateServiceInterface $trackUpdateService,
    ): array|Response|string {
        $trackDto = $trackDataProvider->getOne($id);

        if (!$trackDto) {
            throw new NotFoundHttpException();
        }

        $trackUpdateForm = new TrackUpdateForm($trackDto);

        if ($request->isAjax && $trackUpdateForm->load($request->post())) {
            $response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($trackUpdateForm);
        }

        if ($request->isPost && $trackUpdateForm->load($request->post()) && $trackUpdateForm->validate()) {
            $id = $trackUpdateService->handle($trackUpdateForm);

            if (0 !== $id) {
                $session->setFlash('success', 'Трек успешно обновлен');

                return $this->redirect(['view', 'id' => $id]);
            }

            $session->setFlash('error', 'Не удалось обновить трек');
        }

        return $this->render('update', ['updateForm' => $trackUpdateForm]);
    }

    public function actionView(
        int $id,
        TrackDataProviderInterface $trackDataProvider
    ): array|Response|string {
        $trackDto = $trackDataProvider->getOne($id);

        if (!$trackDto) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', ['trackDto' => $trackDto]);
    }

    public function actionDelete(
        int $id,
        TrackDeleteServiceInterface $trackDeleteService,
        Session $session
    ): array|Response|string {
        $result = $trackDeleteService->handle($id);

        if (!$result) {
            $session->setFlash('success', 'Трек успешно обновлен');

            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->redirect('index');
    }
}
