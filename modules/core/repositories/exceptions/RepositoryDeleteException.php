<?php

declare(strict_types=1);

namespace app\modules\core\repositories\exceptions;

use RuntimeException;
use Throwable;

/**
 * Exception thrown when a repository fails to delete a model.
 *
 * This exception should be used to indicate errors that occur
 * during delete operations in a repository.
 */
class RepositoryDeleteException extends RuntimeException
{
    /**
     * Constructs a new RepositoryDeleteException.
     *
     * @param string $message a description of the error
     * @param int $code the exception code
     * @param null|Throwable $previous the previous exception
     */
    public function __construct(string $message = 'Repository delete error', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
