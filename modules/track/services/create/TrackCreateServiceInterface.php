<?php

declare(strict_types=1);

namespace app\modules\track\services\create;

use app\modules\track\services\create\input\TrackCreateInputInterface;

interface TrackCreateServiceInterface
{
    public function handle(TrackCreateInputInterface $trackCreateInput): int;
}
