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
 * Компонент для сбора метрик (latency и ошибок).
 *
 * Позволяет отслеживать время выполнения запросов и ошибки приложения.
 * Метрики отправляются в сервис, реализующий MetricsServiceInterface.
 *
 * @property bool $enabled Включает или выключает сбор метрик.
 * @property array<string> $excludeRoutes Список шаблонов маршрутов, исключённых из сбора метрик.
 */
class MetricsComponent extends Component implements BootstrapInterface
{
    /**
     * Включает или выключает сбор метрик.
     */
    public bool $enabled = true;

    /**
     * Список шаблонов маршрутов, которые нужно исключить из сбора метрик.
     * Примеры шаблонов: 'site/*', 'api/v1/*'.
     *
     * @var array<string>
     */
    public array $excludeRoutes = [];

    private ?float $startTime = null;
    private ?MetricsServiceInterface $metricsService = null;

    /**
     * Инициализация компонента и регистрация обработчиков событий приложения.
     *
     * @param YiiBaseApplication $app экземпляр приложения Yii
     */
    public function bootstrap($app): void
    {
        if (!$this->enabled) {
            return;
        }

        $app->on(YiiBaseApplication::EVENT_BEFORE_REQUEST, [$this, 'beforeRequest']);
        $app->on(YiiBaseApplication::EVENT_AFTER_REQUEST, [$this, 'afterRequest']);

        // Регистрируем обработчик ошибок перед action
        $app->on(YiiBaseApplication::EVENT_BEFORE_ACTION, function (): void {
            set_error_handler([$this, 'errorHandler']);
        });
    }

    /**
     * Запоминает время начала запроса.
     */
    public function beforeRequest(): void
    {
        $this->startTime = microtime(true);
    }

    /**
     * Срабатывает после ответа — отправляет метрику в сервис.
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
            // Не бросаем исключения из компонента метрик
        }
    }

    /**
     * Обработчик ошибок. Фиксирует ошибку в сервисе метрик.
     *
     * @param int $errno код ошибки
     * @param string $errMessage сообщение об ошибке
     * @param string $errFile файл, в котором произошла ошибка
     * @param int $errLine строка, на которой произошла ошибка
     *
     * @return bool возвращает false для передачи обработки другим обработчикам
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
            // Игнорируем ошибки внутри обработчика ошибок
        }

        return false;
    }

    /**
     * Получает сервис метрик из контейнера зависимостей.
     *
     * @throws Exception если сервис не зарегистрирован или неверного типа
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
     * Проверяет, исключён ли маршрут из сбора метрик.
     *
     * @param string $route маршрут запроса
     */
    private function isExcluded(string $route): bool
    {
        foreach ($this->excludeRoutes as $excludeRoute) {
            if (!is_string($excludeRoute)) {
                continue;
            }

            // Используем FNM_PATHNAME для предсказуемого поведения с путями
            if (fnmatch($excludeRoute, $route, FNM_PATHNAME)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Преобразует код ошибки PHP в строковое представление.
     *
     * @param int $code код ошибки
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
