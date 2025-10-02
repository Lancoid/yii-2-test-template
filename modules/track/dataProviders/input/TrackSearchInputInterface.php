<?php

declare(strict_types=1);

namespace app\modules\track\dataProviders\input;

interface TrackSearchInputInterface
{
    public function getId(): ?int;

    public function getNumber(): ?string;

    public function getStatus(): ?string;

    public function getPage(): int;
}
