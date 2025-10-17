<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\exceptions;

use RuntimeException;
use Throwable;

/**
 * Exception thrown when a user permission is not found.
 */
class UserPermissionNotFoundException extends RuntimeException
{
    /**
     * Constructs a new UserPermissionNotFoundException.
     *
     * @param null|string $permission the missing permission name
     * @param int $code the exception code (default is 0)
     * @param null|Throwable $previous the previous exception used for exception chaining
     */
    public function __construct(?string $permission, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Permission ' . ($permission ?? 'null') . ' not found.', $code, $previous);
    }
}
