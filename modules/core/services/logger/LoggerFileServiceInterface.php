<?php

declare(strict_types=1);

namespace app\modules\core\services\logger;

/**
 * Interface for file logger service.
 * Provides methods to log error, warning, info messages and flush logs.
 */
interface LoggerFileServiceInterface
{
    /**
     * Logs an error message.
     *
     * @param array|string $data Message data
     * @param string $category Log category
     */
    public function error(array|string $data, string $category): void;

    /**
     * Logs a warning message.
     *
     * @param array|string $data Message data
     * @param string $category Log category
     */
    public function warning(array|string $data, string $category): void;

    /**
     * Logs an info message.
     *
     * @param array|string $data Message data
     * @param string $category Log category
     */
    public function info(array|string $data, string $category): void;

    /**
     * Flushes log messages from memory to targets.
     *
     * @param bool $final Whether this is a final call during a request
     */
    public function flush(bool $final = false): void;
}
