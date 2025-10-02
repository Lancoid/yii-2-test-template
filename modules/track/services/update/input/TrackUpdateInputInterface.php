<?php

declare(strict_types=1);

namespace app\modules\track\services\update\input;

interface TrackUpdateInputInterface
{
    public function getId(): ?int;

    public function getNumber(): ?string;

    public function getStatus(): ?string;
}
