<?php

declare(strict_types=1);

namespace app\modules\core\repositories\exceptions;

use RuntimeException;
use Throwable;

class RepositoryNotFoundException extends RuntimeException
{
    /**
     * @var array<string, mixed>
     */
    public array $condition;

    /**
     * @param array<string, mixed> $condition
     */
    public function __construct(?string $model, array $condition = [], int $code = 0, ?Throwable $previous = null)
    {
        $this->condition = $condition;

        parent::__construct('Model ' . ($model ?? '') . ' not found.', $code, $previous);
    }
}
