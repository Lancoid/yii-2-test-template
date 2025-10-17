<?php

declare(strict_types=1);

namespace app\modules\core\services\sentry;

use Throwable;

/**
 * Interface for Sentry error tracking service.
 * Provides methods to export events, capture messages and exceptions, and access configuration.
 */
interface SentryServiceInterface
{
    /**
     * Export all collected Sentry events to the remote service.
     */
    public function export(): void;

    /**
     * Capture a custom message and send it to Sentry.
     *
     * @param string $message Message to capture
     * @param array<string, mixed> $params Additional parameters (tags, level, etc.)
     *
     * @return null|string Event ID if sent, null otherwise
     */
    public function captureMessage(string $message, array $params = []): ?string;

    /**
     * Capture an exception and send it to Sentry.
     *
     * @param Throwable $throwable Exception to capture
     * @param array<string, mixed> $params Additional parameters (tags, level, etc.)
     *
     * @return null|string Event ID if sent, null otherwise
     */
    public function captureException(Throwable $throwable, array $params = []): ?string;

    /**
     * Get the Sentry message builder instance.
     */
    public function getBuilder(): SentryMessageBuilder;

    /**
     * Check if Sentry integration is enabled.
     */
    public function getEnabled(): bool;

    /**
     * Get the Sentry DSN (Data Source Name) string.
     *
     * @return null|string DSN or null if not configured
     */
    public function getDsn(): ?string;

    /**
     * Get client options for Sentry SDK.
     *
     * @return array<string, mixed> Associative array of client options
     */
    public function getClientOptions(): array;
}
