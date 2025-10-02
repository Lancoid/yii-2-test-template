<?php

declare(strict_types=1);

namespace app\modules\track\repositories;

use app\modules\core\repositories\exceptions\RepositoryActionException;
use app\modules\core\repositories\exceptions\RepositoryDeleteException;
use app\modules\core\repositories\exceptions\RepositoryNotFoundException;
use app\modules\core\repositories\exceptions\RepositorySaveException;
use app\modules\track\dataProviders\dto\TrackDataProviderListViewDto;
use app\modules\track\dataProviders\input\TrackSearchInputInterface;
use app\modules\track\models\Track;
use app\modules\track\services\dto\TrackDto;
use RuntimeException;
use Throwable;

class TrackRepository implements TrackRepositoryInterface
{
    private const int LIST_VIEW_LIMIT = 10;

    public function save(TrackDto $trackDto): int
    {
        $model = in_array($trackDto->getId(), [null, 0], true)
            ? new Track()
            : $this->findModelId($trackDto->getId());

        $model = $this->fillModel($model, $trackDto);

        try {
            if (!$model->save(false)) {
                throw new RuntimeException('Saving error.');
            }

            if (!$model->id) {
                throw new RuntimeException('Saving error.');
            }
        } catch (Throwable $exception) {
            throw new RepositorySaveException('Track - Saving error.', (int)$exception->getCode(), $exception);
        }

        return $model->id;
    }

    public function delete(int $id): bool
    {
        $track = $this->findModelId($id);

        try {
            if (!$track->delete()) {
                throw new RepositoryDeleteException('Unknown delete error');
            }
        } catch (Throwable $exception) {
            throw new RepositoryDeleteException('Deleting error.', $exception->getCode(), $exception);
        }

        return true;
    }

    public function findById(int $id): TrackDto
    {
        $Track = $this->findModelId($id);

        return $this->fillDto($Track);
    }

    public function existById(int $id): bool
    {
        return Track::find()->byId($id)->exists();
    }

    public function findByNumber(string $number, ?int $notId = null): TrackDto
    {
        $number = trim($number);

        if (!$number) {
            throw new RepositoryActionException('Argument number is empty.');
        }

        $trackQuery = Track::find()
            ->byNumber($number);

        if (null !== $notId && 0 !== $notId) {
            $trackQuery->byNotId($notId);
        }

        $model = $trackQuery->one();

        if (!$model instanceof Track) {
            throw new RepositoryNotFoundException(Track::class);
        }

        return $this->fillDto($model);
    }

    public function existByNumber(string $number, ?int $notId = null): bool
    {
        $number = trim($number);

        if (!$number) {
            throw new RepositoryActionException('Argument number is empty.');
        }

        $trackQuery = Track::find()
            ->byNumber($number);

        if (!empty($notId)) {
            $trackQuery->byNotId($notId);
        }

        return $trackQuery->exists();
    }

    public function findForListView(TrackSearchInputInterface $trackSearchInput): TrackDataProviderListViewDto
    {
        $trackQuery = Track::find();

        if (!empty($trackSearchInput->getId())) {
            $trackQuery->byId($trackSearchInput->getId());
        }

        if (!empty($trackSearchInput->getNumber())) {
            $trackQuery->byNumber($trackSearchInput->getNumber());
        }

        if (!empty($trackSearchInput->getStatus())) {
            $trackQuery->byStatus($trackSearchInput->getStatus());
        }

        $trackQuery->orderBy('created_at desc');

        $trackDataProviderListViewDto = new TrackDataProviderListViewDto();

        $trackDataProviderListViewDto->setTotalCount($trackQuery->count('DISTINCT id'));

        if ($trackSearchInput->getPage() > 1 && $trackDataProviderListViewDto->getTotalCount() > self::LIST_VIEW_LIMIT) {
            $trackQuery->offset(self::LIST_VIEW_LIMIT * ($trackSearchInput->getPage() - 1));
        }

        $trackQuery->limit(self::LIST_VIEW_LIMIT)
            ->orderBy(['created_at' => SORT_DESC]);

        foreach ($trackQuery->all() as $track) {
            $trackDataProviderListViewDto->setDto($this->fillDto($track));
        }

        return $trackDataProviderListViewDto;
    }

    private function findModelId(int $id): Track
    {
        if (!$model = Track::findOne($id)) {
            throw new RepositoryNotFoundException(Track::class);
        }

        return $model;
    }

    private function fillModel(Track $track, TrackDto $trackDto): Track
    {
        $track->id = $trackDto->getId();
        $track->created_at = $trackDto->getCreatedAt();
        $track->updated_at = $trackDto->getUpdatedAt();
        $track->number = $trackDto->getNumber();
        $track->status = $trackDto->getStatus();

        return $track;
    }

    private function fillDto(Track $track): TrackDto
    {
        $trackDto = new TrackDto();

        $trackDto->setId($track->id)
            ->setCreatedAt($track->created_at)
            ->setUpdatedAt($track->updated_at)
            ->setNumber($track->number)
            ->setStatus($track->status);

        return $trackDto;
    }
}
