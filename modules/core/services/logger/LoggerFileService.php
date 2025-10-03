<?php

declare(strict_types=1);

namespace app\modules\core\services\logger;

use yii\log\Logger;

class LoggerFileService implements LoggerFileServiceInterface
{
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function error(array|string $data, string $category): void
    {
        $this->logger->log($data, Logger::LEVEL_ERROR, $category);
    }

    public function warning(array|string $data, string $category): void
    {
        $this->logger->log($data, Logger::LEVEL_WARNING, $category);
    }

    public function info(array|string $data, string $category): void
    {
        $this->logger->log($data, Logger::LEVEL_INFO, $category);
    }

    public function flush(bool $final = false): void
    {
        $this->logger->flush($final);
    }
}
