<?php

declare(strict_types=1);

namespace app\modules\core\components\logTarget;

use app\modules\user\models\User as AppWebUser;
use Sentry\ClientBuilder;
use Sentry\Event;
use Sentry\EventHint;
use Sentry\Integration\ErrorListenerIntegration;
use Sentry\Integration\ExceptionListenerIntegration;
use Sentry\Integration\FatalErrorListenerIntegration;
use Sentry\Integration\IntegrationInterface;
use Sentry\SentrySdk;
use Sentry\Severity;
use Sentry\State\Scope;
use Throwable;
use Yii;
use yii\helpers\ArrayHelper;
use yii\log\Logger;
use yii\log\Target;
use yii\web\Request;
use yii\web\User as YiiWebUser;

use function Sentry\captureEvent;
use function Sentry\captureException;
use function Sentry\withScope;

/**
 * Sentry log target for Yii2 application.
 *
 * Sends log messages and exceptions to Sentry with user and request context.
 */
class SentryTarget extends Target
{
    /**
     * @var string sentry DSN key
     */
    public string $dsn;

    /**
     * @var array<string, mixed> sentry client options
     */
    public array $clientOptions = [];

    /**
     * @var bool Whether to include context information (user, system variables, etc.).
     */
    public bool $context = true;

    /**
     * @var bool whether Sentry logging is enabled
     */
    public bool $enabled = false;

    /**
     * @var null|callable callback to modify extra data
     */
    public $extraCallback;

    /**
     * SentryTarget constructor.
     *
     * @param array<string, mixed> $config configuration array
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $userOptions = array_merge(['dsn' => $this->dsn], $this->clientOptions);
        $clientBuilder = ClientBuilder::create($userOptions);

        $options = $clientBuilder->getOptions();

        /* Filters out error, exception, and fatal error integrations. */
        $options->setIntegrations(static fn (array $integrations): array => array_filter(
            $integrations,
            static function (IntegrationInterface $integration): bool {
                return !($integration instanceof ErrorListenerIntegration
                    || $integration instanceof ExceptionListenerIntegration
                    || $integration instanceof FatalErrorListenerIntegration);
            }
        ));

        SentrySdk::init()->bindClient($clientBuilder->getClient());
    }

    /**
     * Returns context message (empty for Sentry).
     */
    protected function getContextMessage(): string
    {
        return '';
    }

    /**
     * Exports log messages to Sentry.
     */
    public function export(): void
    {
        /** @var array<array<int, mixed>> $message */
        foreach ($this->messages as $message) {
            /**
             * @var mixed $text
             * @var int $level
             * @var string $category
             */
            [$text, $level, $category] = $message;

            $data = [
                'message' => '',
                'tags' => ['category' => $category],
                'extra' => [],
                'userData' => [],
            ];

            $request = Yii::$app->getRequest();
            if ($request instanceof Request && $request->getUserIP()) {
                $data['userData']['ip_address'] = $request->getUserIP();
            }

            try {
                /** @var null|YiiWebUser<AppWebUser> $user */
                $user = Yii::$app->has('user', true) ? Yii::$app->get('user', false) : null;
                if ($user && ($identity = $user->getIdentity(false))) {
                    $data['userData']['id'] = $identity->getId();
                }
            } catch (Throwable) {
            }

            withScope(function (Scope $scope) use ($text, $level, $data): void {
                if (is_array($text)) {
                    if (isset($text['msg'])) {
                        /* @phpstan-ignore-next-line */
                        $data['message'] = (string)$text['msg'];
                        unset($text['msg']);
                    }

                    if (isset($text['message'])) {
                        /* @phpstan-ignore-next-line */
                        $data['message'] = (string)$text['message'];
                        unset($text['message']);
                    }

                    if (isset($text['tags'])) {
                        /* @phpstan-ignore-next-line */
                        $data['tags'] = ArrayHelper::merge($data['tags'], $text['tags']);
                        unset($text['tags']);
                    }

                    if (isset($text['exception']) && $text['exception'] instanceof Throwable) {
                        $data['exception'] = $text['exception'];
                        unset($text['exception']);
                    }

                    $data['extra'] = $text;
                } else {
                    /* @phpstan-ignore-next-line */
                    $data['message'] = (string)$text;
                }

                if ($this->context) {
                    $data['extra']['context'] = parent::getContextMessage();
                }

                $data = $this->runExtraCallback($text, $data);

                /* @phpstan-ignore-next-line */
                $scope->setUser($data['userData']);

                /* @phpstan-ignore-next-line */
                foreach ($data['extra'] as $key => $value) {
                    /* @phpstan-ignore-next-line */
                    $scope->setExtra((string)$key, $value);
                }

                /* @phpstan-ignore-next-line */
                foreach ($data['tags'] as $key => $value) {
                    if ($value) {
                        /* @phpstan-ignore-next-line */
                        $scope->setTag($key, $value);
                    }
                }

                if ($text instanceof Throwable) {
                    captureException($text);
                } else {
                    $event = Event::createEvent();
                    /* @phpstan-ignore-next-line */
                    $event->setMessage($data['message']);
                    $event->setLevel($this->getLogLevel($level));

                    /* @phpstan-ignore-next-line */
                    captureEvent($event, EventHint::fromArray(array_filter([
                        'exception' => $data['exception'] ?? null,
                    ])));
                }
            });
        }
    }

    /**
     * Calls the extra callback if it exists.
     *
     * @param mixed $text log message text
     * @param array<string, mixed> $data log data
     *
     * @return array<string, mixed> modified log data
     */
    public function runExtraCallback(mixed $text, array $data): array
    {
        if (is_callable($this->extraCallback)) {
            $data['extra'] = call_user_func($this->extraCallback, $text, $data['extra'] ?? []);
        }

        return $data;
    }

    /**
     * Translates Yii2 log levels to Sentry Severity.
     *
     * @param int $level yii2 log level
     *
     * @return Severity sentry severity
     */
    protected function getLogLevel(int $level): Severity
    {
        return match ($level) {
            Logger::LEVEL_PROFILE, Logger::LEVEL_PROFILE_BEGIN, Logger::LEVEL_PROFILE_END, Logger::LEVEL_TRACE => Severity::debug(),
            Logger::LEVEL_WARNING => Severity::warning(),
            Logger::LEVEL_ERROR => Severity::error(),
            default => Severity::info(),
        };
    }
}
