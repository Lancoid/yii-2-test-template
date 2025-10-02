<?php

declare(strict_types=1);

namespace app\modules\track\services\update\hydrate;

use app\modules\track\services\dto\TrackDto;
use app\modules\track\services\update\input\TrackUpdateInputInterface;
use DateTimeImmutable;

class HydrateUpdateTrackDto
{
    public function hydrate(TrackDto $trackDto, TrackUpdateInputInterface $trackUpdateInput): TrackDto
    {
        $dateTime = new DateTimeImmutable();

        return $trackDto
            ->setUpdatedAt($dateTime->getTimestamp())
            ->setNumber($trackUpdateInput->getNumber())
            ->setStatus($trackUpdateInput->getStatus());
    }
}
