<?php

declare(strict_types=1);

namespace app\modules\track\services\update;

use app\modules\track\repositories\TrackRepositoryInterface;
use app\modules\track\services\dto\TrackDto;
use app\modules\track\services\update\hydrate\HydrateUpdateTrackDto;
use app\modules\track\services\update\input\TrackUpdateInputInterface;

readonly class TrackUpdateService implements TrackUpdateServiceInterface
{
    public function __construct(
        private HydrateUpdateTrackDto $hydrateUpdateTrackDto,
        private TrackRepositoryInterface $trackRepository
    ) {}

    public function handle(TrackUpdateInputInterface $trackUpdateInput): int
    {
        $trackDto = $this->trackRepository->findById($trackUpdateInput->getId());

        $trackDto = $this->fillDto($trackDto, $trackUpdateInput);

        return $this->trackRepository->save($trackDto);
    }

    private function fillDto(TrackDto $trackDto, TrackUpdateInputInterface $trackUpdateInput): TrackDto
    {
        return $this->hydrateUpdateTrackDto->hydrate($trackDto, $trackUpdateInput);
    }
}
