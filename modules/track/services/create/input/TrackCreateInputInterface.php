<?php

declare(strict_types=1);

namespace app\modules\track\services\create\input;

interface TrackCreateInputInterface
{
    public function getNumber(): ?string;

    public function getStatus(): ?string;
}
