<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\exceptions;

use RuntimeException;
use Throwable;

class UserPermissionNotFoundException extends RuntimeException
{
    public function __construct(?string $permission, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Permission ' . ($permission ?? 'null') . ' not found.', $code, $previous);
    }
}
