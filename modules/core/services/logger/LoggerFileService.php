<?php

declare(strict_types=1);

namespace app\modules\core\services\logger;

use yii\log\Logger;

readonly class LoggerFileService implements LoggerFileServiceInterface
{
    private Logger $logger;

    private const array SENSITIVE_KEYS = [
        'pass',
        'password',
        'pwd',
        'secret',
        'token',
    ];

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function error(array|string $data, string $category): void
    {
        $maskedData = $this->maskData($data);

        $this->logger->log($maskedData, Logger::LEVEL_ERROR, $category);
    }

    public function warning(array|string $data, string $category): void
    {
        $maskedData = $this->maskData($data);

        $this->logger->log($maskedData, Logger::LEVEL_WARNING, $category);
    }

    public function info(array|string $data, string $category): void
    {
        $maskedData = $this->maskData($data);

        $this->logger->log($maskedData, Logger::LEVEL_INFO, $category);
    }

    public function flush(bool $final = false): void
    {
        $this->logger->flush($final);
    }

    private function maskData(array|string $data): array|string
    {
        if (is_string($data)) {
            return $data;
        }

        return array_map(
            fn ($value, $key) => in_array(strtolower($key), self::SENSITIVE_KEYS, true) ? '***' : $value,
            $data,
            array_keys($data)
        );
    }
}
