<?php

declare(strict_types=1);

namespace app\modules\user\services\login\input;

interface UserLoginInputInterface
{
    public function getEmail(): ?string;

    public function getPassword(): ?string;

    public function getRememberMe(): bool;
}
