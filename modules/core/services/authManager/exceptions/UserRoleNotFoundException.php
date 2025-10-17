<?php

declare(strict_types=1);

namespace app\modules\core\services\authManager\exceptions;

use RuntimeException;
use Throwable;

/**
 * Exception thrown when a user role is not found.
 */
class UserRoleNotFoundException extends RuntimeException
{
    /**
     * Constructs a new UserRoleNotFoundException.
     *
     * @param null|string $role the missing role name
     * @param int $code the exception code (default is 0)
     * @param null|Throwable $previous the previous exception used for exception chaining
     */
    public function __construct(?string $role, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Role ' . ($role ?? 'null') . ' not found.', $code, $previous);
    }
}
