<?php

declare(strict_types=1);

namespace app\modules\track\services\update;

use app\modules\core\services\exceptions\ServiceFormValidationException;
use app\modules\track\repositories\TrackRepositoryInterface;
use app\modules\track\services\dto\TrackDto;
use app\modules\track\services\update\hydrate\HydrateUpdateTrackDto;
use app\modules\track\services\update\input\TrackUpdateInputInterface;
use app\modules\track\TrackModule;

readonly class TrackUpdateService implements TrackUpdateServiceInterface
{
    public function __construct(
        private HydrateUpdateTrackDto $hydrateUpdateTrackDto,
        private TrackRepositoryInterface $trackRepository
    ) {}

    public function handle(TrackUpdateInputInterface $trackUpdateInput): int
    {
        if (empty($trackUpdateInput->getId())) {
            throw new ServiceFormValidationException('email', TrackModule::t('crud_form', 'ID not found'));
        }

        $trackDto = $this->trackRepository->findById($trackUpdateInput->getId());

        $trackDto = $this->fillDto($trackDto, $trackUpdateInput);

        return $this->trackRepository->save($trackDto);
    }

    private function fillDto(TrackDto $trackDto, TrackUpdateInputInterface $trackUpdateInput): TrackDto
    {
        return $this->hydrateUpdateTrackDto->hydrate($trackDto, $trackUpdateInput);
    }
}
