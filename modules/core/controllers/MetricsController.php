<?php

declare(strict_types=1);

namespace app\modules\core\controllers;

use app\modules\core\services\metrics\MetricsServiceInterface;
use app\modules\user\dictionaries\UserPermissionDictionary;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * MetricsController provides endpoints for metrics summary and health checks.
 *
 * Access restricted to administrators.
 */
class MetricsController extends Controller
{
    /**
     * Configures access control for the controller.
     *
     * @return array<string, mixed> the behaviors configuration
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [UserPermissionDictionary::ROLE_ADMIN],
                    ],
                ],
            ],
        ];
    }

    /**
     * Returns metrics summary for the specified period.
     *
     * Example usage: `/core/metrics/summary?minutes=60`
     *
     * @param MetricsServiceInterface $metricsService service for metrics aggregation
     * @param Response $response yii response object
     * @param int $minutes number of minutes to summarize (default: 60)
     *
     * @return Response JSON response with metrics summary
     */
    public function actionSummary(
        MetricsServiceInterface $metricsService,
        Response $response,
        int $minutes = 60,
    ): Response {
        $response->format = Response::FORMAT_JSON;

        return $this->asJson($metricsService->getSummary($minutes));
    }

    /**
     * Health check endpoint for monitoring system status.
     *
     * Example usage: `/core/metrics/health`
     *
     * @param MetricsServiceInterface $metricsService service for metrics aggregation
     * @param Response $response yii response object
     *
     * @return Response JSON response with health status
     */
    public function actionHealth(
        MetricsServiceInterface $metricsService,
        Response $response
    ): Response {
        $response->format = Response::FORMAT_JSON;

        $summary = $metricsService->getSummary(5); // Last 5 minutes

        $health = [
            'status' => 'ok',
            'timestamp' => date('Y-m-d H:i:s'),
            'metrics' => [
                'requests_per_minute' => round($summary['requests']['total'] / 5, 2),
                'error_rate' => $summary['errors']['error_rate'],
                'avg_response_time' => $summary['requests']['response_times']['avg'] ?? null,
            ],
        ];

        // Mark as unhealthy if error rate > 5% or avg response time > 1000 ms
        if ($summary['errors']['error_rate'] > 5
            || ($summary['requests']['response_times']['avg'] ?? 0) > 1000) {
            $health['status'] = 'degraded';
            $response->statusCode = 503;
        }

        return $this->asJson($health);
    }
}
