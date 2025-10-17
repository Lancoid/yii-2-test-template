<?php

declare(strict_types=1);

namespace app\modules\core\services\sentry;

use Sentry\EventId;
use Sentry\State\Scope;
use Throwable;
use yii\base\Exception;

use function Sentry\captureException;
use function Sentry\captureMessage;
use function Sentry\configureScope;
use function Sentry\init;

/**
 * Builder for Sentry messages and exceptions.
 */
class SentryMessageBuilder
{
    private const ERROR_NO_DATA = 'Message or exception must be provided for Sentry.';

    protected ?string $message = null;
    protected ?Throwable $exception = null;

    /** @var array<string, string> */
    protected array $tags = [];

    /** @var array<string, mixed> */
    protected array $extra = [];

    public function __construct(private readonly SentryServiceInterface $sentryService) {}

    /**
     * Set message for Sentry.
     */
    public function setMessage(string $message): self
    {
        $this->exception = null;
        $this->message = $message;

        return $this;
    }

    /**
     * Set exception for Sentry.
     */
    public function setException(Throwable $throwable): self
    {
        $this->message = null;
        $this->exception = $throwable;

        return $this;
    }

    /**
     * Add tags for Sentry event.
     *
     * @param array<string, string> $tags
     */
    public function setTags(array $tags): self
    {
        $this->tags = array_merge($this->tags, $tags);

        return $this;
    }

    /**
     * Add extra data for Sentry event.
     *
     * @param array<string, mixed> $extra
     */
    public function setExtra(array $extra): self
    {
        $this->extra = array_merge($this->extra, $extra);

        return $this;
    }

    /**
     * Send event to Sentry.
     *
     * @throws Exception
     */
    public function send(): ?string
    {
        if (!$this->initClient()) {
            return null;
        }

        if (empty($this->message) && !$this->exception instanceof Throwable) {
            throw new Exception(self::ERROR_NO_DATA);
        }

        $this->configure();

        return $this->capture();
    }

    protected function capture(): ?string
    {
        if (!empty($this->message)) {
            $eventId = captureMessage($this->message);
            if ($eventId instanceof EventId) {
                return $eventId->__toString();
            }
        }

        if ($this->exception instanceof Throwable) {
            $eventId = captureException($this->exception);
            if ($eventId instanceof EventId) {
                return $eventId->__toString();
            }
        }

        return null;
    }

    protected function configure(): void
    {
        configureScope(function (Scope $scope): void {
            if (!empty($this->extra)) {
                $scope->setExtras($this->extra);
            }
            if (!empty($this->tags)) {
                $scope->setTags($this->tags);
            }
        });
    }

    private function initClient(): bool
    {
        if (!$this->sentryService->getEnabled()) {
            return false;
        }

        $options = array_merge(
            ['dsn' => $this->sentryService->getDsn()],
            $this->sentryService->getClientOptions()
        );

        /* @phpstan-ignore-next-line */
        init($options);

        return true;
    }
}
