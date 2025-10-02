<?php

declare(strict_types=1);

namespace app\modules\track\services\delete;

interface TrackDeleteServiceInterface
{
    public function handle(int $id): bool;
}
