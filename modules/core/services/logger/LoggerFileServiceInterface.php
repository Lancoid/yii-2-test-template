<?php

declare(strict_types=1);

namespace app\modules\core\services\logger;

interface LoggerFileServiceInterface
{
    public function error(array|string $data, string $category): void;

    public function warning(array|string $data, string $category): void;

    public function info(array|string $data, string $category): void;

    /**
     * @description Flushes log messages from memory to targets.
     *
     * @param bool $final whether this is a final call during a request
     */
    public function flush(bool $final = false): void;
}
