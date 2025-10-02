<?php

declare(strict_types=1);

namespace app\modules\track\dataProviders;

use app\modules\core\repositories\exceptions\RepositoryNotFoundException;
use app\modules\core\services\sentry\SentryServiceInterface;
use app\modules\track\dataProviders\dto\TrackDataProviderListViewDto;
use app\modules\track\dataProviders\input\TrackSearchInputInterface;
use app\modules\track\repositories\TrackRepositoryInterface;
use app\modules\track\services\dto\TrackDto;
use Exception;
use Throwable;

readonly class TrackDataProvider implements TrackDataProviderInterface
{
    public function __construct(
        private TrackRepositoryInterface $trackRepository,
        private SentryServiceInterface $sentryService
    ) {}

    public function getOne(int $id): ?TrackDto
    {
        try {
            return $this->trackRepository->findById($id);
        } catch (Throwable $throwable) {
            $exception = new Exception('TrackDataProvider::getOne ' . $throwable->getMessage(), $throwable->getCode(), $throwable);

            $this->sentryService->captureException($exception, [
                'id' => $id,
                'error_message' => $exception->getMessage(),
            ]);
        }

        return null;
    }

    public function getListView(TrackSearchInputInterface $trackSearchInput): TrackDataProviderListViewDto
    {
        $result = new TrackDataProviderListViewDto();

        try {
            $result = $this->trackRepository->findForListView($trackSearchInput);
        } catch (RepositoryNotFoundException) {
            return $result;
        } catch (Throwable $throwable) {
            $exception = new Exception('Track receiving error', (int)$throwable->getCode(), $throwable);

            $this->sentryService->captureException($exception, [
                'id' => $trackSearchInput->getId(),
                'number' => $trackSearchInput->getNumber(),
                'status' => $trackSearchInput->getStatus(),
            ]);
        }

        return $result;
    }
}
