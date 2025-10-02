<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\exceptions;

use RuntimeException;
use Throwable;

class UserRoleNotFoundException extends RuntimeException
{
    public function __construct(?string $role, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Role ' . ($role ?? 'null') . ' not found.', $code, $previous);
    }
}
