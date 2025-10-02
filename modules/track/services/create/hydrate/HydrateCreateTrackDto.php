<?php

declare(strict_types=1);

namespace app\modules\track\services\create\hydrate;

use app\modules\track\services\create\input\TrackCreateInputInterface;
use app\modules\track\services\dto\TrackDto;
use DateTimeImmutable;

class HydrateCreateTrackDto
{
    public function hydrate(TrackCreateInputInterface $trackCreateInput): TrackDto
    {
        $dateTime = new DateTimeImmutable();

        return new TrackDto()
            ->setCreatedAt($dateTime->getTimestamp())
            ->setUpdatedAt($dateTime->getTimestamp())
            ->setNumber($trackCreateInput->getNumber())
            ->setStatus($trackCreateInput->getStatus());
    }
}
