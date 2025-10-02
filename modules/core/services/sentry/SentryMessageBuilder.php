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

class SentryMessageBuilder
{
    protected ?string $message = null;

    protected ?Throwable $exception = null;

    /**
     * @var array<string, string>
     */
    protected array $tags = [];

    /**
     * @var array<string, mixed>
     */
    protected array $extra = [];

    public function __construct(private readonly SentryServiceInterface $sentryService) {}

    public function setMessage(string $message): self
    {
        $this->exception = null;
        $this->message = $message;

        return $this;
    }

    public function setException(Throwable $throwable): self
    {
        $this->message = null;
        $this->exception = $throwable;

        return $this;
    }

    /**
     * @param array<string, string> $tags
     */
    public function setTags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @param array<string, mixed> $extra
     */
    public function setExtra(array $extra): self
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function send(): ?string
    {
        if (!$this->initClient()) {
            return null;
        }

        if (null === $this->message && !$this->exception instanceof Throwable) {
            throw new Exception('Для передачи информации в Sentry необходимо указать сообщение или исключение');
        }

        $this->configure();

        return $this->capture();
    }

    protected function capture(): ?string
    {
        if (null !== $this->message && '' !== $this->message && '0' !== $this->message) {
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
            if ([] !== $this->extra) {
                $scope->setExtras($this->extra);
            }

            if ([] !== $this->tags) {
                $scope->setTags($this->tags);
            }
        });
    }

    private function initClient(): bool
    {
        if (!$this->sentryService->getEnabled()) {
            return false;
        }

        /** @var array<string, mixed> $options */
        $options = array_merge(
            ['dsn' => $this->sentryService->getDsn()],
            $this->sentryService->getClientOptions()
        );

        /* @phpstan-ignore-next-line */
        init($options);

        return true;
    }
}
