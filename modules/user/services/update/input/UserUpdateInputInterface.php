<?php

declare(strict_types=1);

namespace app\modules\user\services\update\input;

interface UserUpdateInputInterface
{
    public function getId(): ?int;

    public function getUsername(): ?string;

    public function getEmail(): ?string;

    public function getPhone(): ?string;

    public function getStatus(): ?int;

    public function getRole(): ?string;
}
