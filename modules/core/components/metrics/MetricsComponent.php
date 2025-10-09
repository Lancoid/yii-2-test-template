<?php

declare(strict_types=1);

namespace app\modules\core\components\metrics;

use app\modules\core\dictionaries\HttpCodeDictionary;
use app\modules\core\services\metrics\MetricsServiceInterface;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Component;

class MetricsComponent extends Component implements BootstrapInterface
{
    public bool $enabled = true;

    /**
     * @var array<string> The list of routes that should be excluded from the collection of metrics
     */
    public array $excludeRoutes = [];

    private ?float $startTime = null;
    private ?MetricsServiceInterface $metricsService = null;

    public function bootstrap($app): void
    {
        if (!$this->enabled) {
            return;
        }

        $app->on(Application::EVENT_BEFORE_REQUEST, [$this, 'beforeRequest']);
        $app->on(Application::EVENT_AFTER_REQUEST, [$this, 'afterRequest']);

        // Track errors and exceptions
        $app->on(Application::EVENT_BEFORE_ACTION, function ($event): void {
            /* @phpstan-ignore-next-line */
            set_error_handler([$this, 'errorHandler']);
        });
    }

    public function beforeRequest(): void
    {
        $this->startTime = microtime(true);
    }

    public function afterRequest(): void
    {
        if (null === $this->startTime) {
            return;
        }

        $app = Yii::$app;
        $request = $app->request;
        $response = $app->response;
        $route = $app->requestedRoute ?? 'unknown';

        // Skip excluded routes
        if ($this->isExcluded($route)) {
            return;
        }

        $duration = (microtime(true) - $this->startTime) * 1000; // Convert to milliseconds
        $statusCode = $response->statusCode ?: HttpCodeDictionary::INTERNAL_SERVER_ERROR;
        $method = $request->method ?: 'UNKNOWN';

        $this->getMetricsService()->recordRequest($route, $duration, $statusCode, $method);
    }

    public function errorHandler(int $code, string $string, string $file, string $line): bool
    {
        $route = Yii::$app->requestedRoute ?? null;

        $this->getMetricsService()
            ->recordError(
                $this->getErrorType($code),
                $string,
                $route,
                [
                    'file' => $file,
                    'line' => $line,
                ]
            );

        return false; // Let other error handlers process this
    }

    private function getMetricsService(): MetricsServiceInterface
    {
        if (null === $this->metricsService) {
            $this->metricsService = Yii::$container->get(MetricsServiceInterface::class);
        }

        return $this->metricsService;
    }

    private function isExcluded(string $route): bool
    {
        return array_any(
            $this->excludeRoutes,
            fn ($excludeRoute): bool => fnmatch($excludeRoute, $route)
        );
    }

    private function getErrorType(int $code): string
    {
        $types = [
            E_ERROR => 'E_ERROR',
            E_WARNING => 'E_WARNING',
            E_PARSE => 'E_PARSE',
            E_NOTICE => 'E_NOTICE',
            E_CORE_ERROR => 'E_CORE_ERROR',
            E_CORE_WARNING => 'E_CORE_WARNING',
            E_COMPILE_ERROR => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING => 'E_COMPILE_WARNING',
            E_USER_ERROR => 'E_USER_ERROR',
            E_USER_WARNING => 'E_USER_WARNING',
            E_USER_NOTICE => 'E_USER_NOTICE',
            E_STRICT => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED => 'E_DEPRECATED',
            E_USER_DEPRECATED => 'E_USER_DEPRECATED',
        ];

        return $types[$code] ?? sprintf('E_UNKNOWN(%d)', $code);
    }
}
