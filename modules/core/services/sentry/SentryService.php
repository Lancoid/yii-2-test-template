<?php

declare(strict_types=1);

namespace app\modules\core\services\sentry;

use app\modules\core\components\logTarget\SentryTarget;
use Throwable;

class SentryService extends SentryTarget implements SentryServiceInterface
{
    public function export(): void
    {
        if (!$this->enabled) {
            return;
        }

        parent::export();
    }

    public function captureMessage(string $message, array $params = []): ?string
    {
        if (!$this->enabled) {
            return null;
        }

        return $this->getBuilder()->setMessage($message)->setExtra($params)->send();
    }

    public function captureException(Throwable $throwable, array $params = []): ?string
    {
        if (!$this->enabled) {
            return null;
        }

        return $this->getBuilder()->setException($throwable)->setExtra($params)->send();
    }

    public function getBuilder(): SentryMessageBuilder
    {
        return new SentryMessageBuilder($this);
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function getDsn(): ?string
    {
        return $this->dsn;
    }

    public function getClientOptions(): array
    {
        return $this->clientOptions;
    }
}
