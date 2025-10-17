<?php

declare(strict_types=1);

namespace app\modules\core\repositories\exceptions;

use RuntimeException;
use Throwable;

/**
 * Exception thrown when a repository cannot find the requested model.
 *
 * This exception is typically used to indicate that a model was not found
 * in the repository based on the provided search condition.
 *
 * @property null|string $model The name of the model that was not found.
 * @property array<string, mixed> $condition The search condition that caused the exception.
 */
class RepositoryNotFoundException extends RuntimeException
{
    /**
     * The name of the model that was not found.
     */
    public readonly ?string $model;

    /**
     * The search condition that caused the exception.
     *
     * @var array<string, mixed>
     */
    public readonly array $condition;

    /**
     * Constructs a new RepositoryNotFoundException.
     *
     * @param null|string $model the name of the model that was not found
     * @param array<string, mixed> $condition the search condition
     * @param int $code the exception code
     * @param null|Throwable $previous the previous exception
     */
    public function __construct(?string $model, array $condition = [], int $code = 0, ?Throwable $previous = null)
    {
        $this->model = $model;
        $this->condition = $condition;

        $message = sprintf(
            'Model %s not found. Condition: %s',
            $model ?? '',
            json_encode($condition, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        parent::__construct($message, $code, $previous);
    }
}
