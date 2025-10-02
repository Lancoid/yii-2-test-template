<?php

declare(strict_types=1);

namespace app\modules\track\services\update;

use app\modules\track\services\update\input\TrackUpdateInputInterface;

interface TrackUpdateServiceInterface
{
    public function handle(TrackUpdateInputInterface $trackUpdateInput): int;
}
