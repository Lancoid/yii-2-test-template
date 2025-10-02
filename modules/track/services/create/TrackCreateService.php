<?php

declare(strict_types=1);

namespace app\modules\track\services\create;

use app\modules\track\repositories\TrackRepositoryInterface;
use app\modules\track\services\create\hydrate\HydrateCreateTrackDto;
use app\modules\track\services\create\input\TrackCreateInputInterface;
use app\modules\track\services\dto\TrackDto;

readonly class TrackCreateService implements TrackCreateServiceInterface
{
    public function __construct(
        private HydrateCreateTrackDto $hydrateCreateTrackDto,
        private TrackRepositoryInterface $trackRepository
    ) {}

    public function handle(TrackCreateInputInterface $trackCreateInput): int
    {
        $trackDto = $this->fillDto($trackCreateInput);

        return $this->trackRepository->save($trackDto);
    }

    private function fillDto(TrackCreateInputInterface $trackCreateInput): TrackDto
    {
        return $this->hydrateCreateTrackDto->hydrate($trackCreateInput);
    }
}
