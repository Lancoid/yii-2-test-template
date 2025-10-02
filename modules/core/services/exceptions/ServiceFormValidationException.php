<?php

declare(strict_types=1);

namespace app\modules\core\services\exceptions;

use RuntimeException;
use Throwable;

class ServiceFormValidationException extends RuntimeException
{
    private ?string $attribute;
    private ?string $errorMessage;

    public function __construct(string $attribute, string $errorMessage, int $code = 0, ?Throwable $previous = null)
    {
        $this->attribute = $attribute;
        $this->errorMessage = $errorMessage;

        parent::__construct($errorMessage, $code, $previous);
    }

    public function getAttribute(): ?string
    {
        return $this->attribute;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
}
