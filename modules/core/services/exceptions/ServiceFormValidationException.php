<?php

declare(strict_types=1);

namespace app\modules\core\services\exceptions;

use RuntimeException;
use Throwable;

/**
 * Exception thrown when a service form validation fails.
 */
class ServiceFormValidationException extends RuntimeException
{
    /**
     * @var string Attribute name that failed validation
     */
    public readonly string $attribute;

    /**
     * @var string Validation error message
     */
    public readonly string $errorMessage;

    /**
     * @param string $attribute Attribute name
     * @param string $errorMessage Error message
     * @param int $code Exception code
     * @param null|Throwable $previous Previous exception
     */
    public function __construct(string $attribute, string $errorMessage, int $code = 0, ?Throwable $previous = null)
    {
        $this->attribute = $attribute;
        $this->errorMessage = $errorMessage;

        parent::__construct($errorMessage, $code, $previous);
    }

    /**
     * Returns the attribute name that failed validation.
     */
    public function getAttribute(): string
    {
        return $this->attribute;
    }

    /**
     * Returns the validation error message.
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
