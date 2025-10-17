<?php

declare(strict_types=1);

namespace app\modules\core\repositories\exceptions;

use RuntimeException;
use Throwable;

/**
 * Exception thrown when a repository action fails.
 *
 * This exception should be used to indicate errors that occur
 * during repository operations, such as saving, updating, or deleting models.
 */
class RepositoryActionException extends RuntimeException
{
    /**
     * Constructs a new RepositoryActionException.
     *
     * @param string $message a description of the error
     * @param int $code the exception code
     * @param null|Throwable $previous the previous exception
     */
    public function __construct(string $message = 'Repository action error', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
