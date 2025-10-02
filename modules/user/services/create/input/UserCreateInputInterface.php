<?php

declare(strict_types=1);

namespace app\modules\user\services\create\input;

interface UserCreateInputInterface
{
    public function getUsername(): ?string;

    public function getPassword(): ?string;

    public function getEmail(): ?string;

    public function getPhone(): ?string;

    public function getStatus(): ?int;

    public function getRole(): ?string;
}
