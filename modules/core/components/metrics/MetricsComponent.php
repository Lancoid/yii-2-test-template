<?php

declare(strict_types=1);

namespace app\modules\core\components\metrics;

use app\modules\core\dictionaries\HttpCodeDictionary;
use app\modules\core\services\metrics\MetricsServiceInterface;
use Exception;
use RuntimeException;
use Throwable;
use Yii;
use yii\base\Application as YiiBaseApplication;
use yii\base\BootstrapInterface;
use yii\base\Component;

/**
 * Component for collecting application metrics (latency and errors).
 *
 * Tracks request execution time and application errors.
 * Metrics are sent to a service implementing MetricsServiceInterface.
 *
 * @property bool $enabled Enables or disables metrics collection.
 * @property array<string> $excludeRoutes List of route patterns to exclude from metrics collection.
 */
class MetricsComponent extends Component implements BootstrapInterface
{
    /**
     * Enables or disables metrics collection.
     */
    public bool $enabled = true;

    /**
     * List of route patterns to exclude from metrics collection.
     * Example patterns: 'site/*', 'api/v1/*'.
     *
     * @var array<string>
     */
    public array $excludeRoutes = [];

    /**
     * Request start time in microseconds.
     */
    private ?float $startTime = null;

    /**
     * Metrics service instance.
     */
    private ?MetricsServiceInterface $metricsService = null;

    /**
     * Initializes the component and registers application event handlers.
     *
     * @param YiiBaseApplication $app yii application instance
     */
    public function bootstrap($app): void
    {
        if (!$this->enabled) {
            return;
        }

        $app->on(YiiBaseApplication::EVENT_BEFORE_REQUEST, [$this, 'beforeRequest']);
        $app->on(YiiBaseApplication::EVENT_AFTER_REQUEST, [$this, 'afterRequest']);

        // Register error handler before action execution
        $app->on(YiiBaseApplication::EVENT_BEFORE_ACTION, function (): void {
            set_error_handler([$this, 'errorHandler']);
        });
    }

    /**
     * Stores the request start time.
     */
    public function beforeRequest(): void
    {
        $this->startTime = microtime(true);
    }

    /**
     * Called after the response is sent — sends metrics to the service.
     */
    public function afterRequest(): void
    {
        if (null === $this->startTime) {
            return;
        }

        $request = Yii::$app->has('request') ? Yii::$app->request : null;
        $response = Yii::$app->has('response') ? Yii::$app->response : null;
        $route = Yii::$app->requestedRoute ?? 'unknown';

        if ($this->isExcluded($route)) {
            return;
        }

        $duration = (microtime(true) - $this->startTime) * 1000.0; // ms
        $statusCode = $response->statusCode ?? HttpCodeDictionary::INTERNAL_SERVER_ERROR;
        $method = $request->method ?? 'UNKNOWN';

        try {
            $this->getMetricsService()->recordRequest($route, $duration, $statusCode, $method);
        } catch (Throwable) {
            // Do not throw exceptions from the metrics component
        }
    }

    /**
     * Error handler. Records the error in the metrics service.
     *
     * @param int $errno error code
     * @param string $errMessage error message
     * @param string $errFile file where the error occurred
     * @param int $errLine line number where the error occurred
     *
     * @return bool always returns false to pass handling to other handlers
     */
    public function errorHandler(int $errno, string $errMessage, string $errFile, int $errLine): bool
    {
        try {
            $route = Yii::$app->requestedRoute ?? null;

            $this->getMetricsService()->recordError(
                $this->getErrorType($errno),
                $errMessage,
                $route,
                [
                    'file' => $errFile,
                    'line' => $errLine,
                ]
            );
        } catch (Throwable) {
            // Ignore errors inside the error handler
        }

        return false;
    }

    /**
     * Returns the metrics service from the dependency injection container.
     *
     * @throws Exception if the service is not registered or of the wrong type
     */
    private function getMetricsService(): MetricsServiceInterface
    {
        if (null === $this->metricsService) {
            $service = Yii::$container->get(MetricsServiceInterface::class);

            if (!$service instanceof MetricsServiceInterface) {
                throw new RuntimeException('MetricsServiceInterface not available in container.');
            }

            $this->metricsService = $service;
        }

        return $this->metricsService;
    }

    /**
     * Checks if the route is excluded from metrics collection.
     *
     * @param string $route request route
     *
     * @return bool true if the route is excluded, false otherwise
     */
    private function isExcluded(string $route): bool
    {
        foreach ($this->excludeRoutes as $excludeRoute) {
            if (!is_string($excludeRoute)) {
                continue;
            }

            // Use FNM_PATHNAME for predictable path matching
            if (fnmatch($excludeRoute, $route, FNM_PATHNAME)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Converts a PHP error code to its string representation.
     *
     * @param int $code error code
     *
     * @return string error type as string
     */
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
