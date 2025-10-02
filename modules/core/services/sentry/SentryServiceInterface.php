<?php

declare(strict_types=1);

namespace app\modules\core\services\sentry;

use Throwable;

interface SentryServiceInterface
{
    public function export(): void;

    /**
     * @param array<string, mixed> $params
     */
    public function captureMessage(string $message, array $params = []): ?string;

    /**
     * @param array<string, mixed> $params
     */
    public function captureException(Throwable $throwable, array $params = []): ?string;

    public function getBuilder(): SentryMessageBuilder;

    public function getEnabled(): bool;

    public function getDsn(): ?string;

    /**
     * @return array<string, mixed>
     */
    public function getClientOptions(): array;
}
