<?php

declare(strict_types=1);

namespace app\modules\core\services\audit;

use app\modules\core\services\logger\LoggerFileServiceInterface;
use app\modules\user\services\web\UserWebServiceInterface;
use Yii;
use yii\web\Request;

readonly class AuditLogService implements AuditLogServiceInterface
{
    public function __construct(
        private bool $enabled = true,
        private LoggerFileServiceInterface $loggerFileService,
        private UserWebServiceInterface $userWebService,
    ) {}

    public function logAuth(string $username, bool $success, ?string $reason = null, array $context = []): void
    {
        if (!$this->enabled) {
            return;
        }

        $action = $success ? 'auth.login.success' : 'auth.login.failed';

        $this->log(
            action: $action,
            userId: null, // Not yet authenticated
            changes: [
                'username' => $username,
                'success' => $success,
                'reason' => $reason,
            ],
            context: array_merge([
                'category' => 'authentication',
            ], $context)
        );
    }

    public function logAccess(
        string $entityType,
        int|string $entityId,
        string $action,
        ?int $userId = null,
        array $context = []
    ): void {
        if (!$this->enabled) {
            return;
        }

        $this->log(
            action: sprintf('%s.%s', $entityType, $action),
            userId: $userId,
            entityType: $entityType,
            entityId: $entityId,
            context: array_merge([
                'category' => 'access',
            ], $context)
        );
    }

    public function logModification(
        string $entityType,
        int|string $entityId,
        string $action,
        array $oldData,
        array $newData,
        ?int $userId = null,
        array $context = []
    ): void {
        if (!$this->enabled) {
            return;
        }

        $changes = $this->calculateChanges($oldData, $newData);

        $this->log(
            action: sprintf('%s.%s', $entityType, $action),
            userId: $userId,
            entityType: $entityType,
            entityId: $entityId,
            changes: $changes,
            context: array_merge([
                'category' => 'modification',
            ], $context)
        );
    }

    public function logSecurity(
        string $event,
        string $severity,
        string $description,
        ?int $userId = null,
        array $context = []
    ): void {
        if (!$this->enabled) {
            return;
        }

        $this->log(
            action: sprintf('security.%s', $event),
            userId: $userId,
            changes: [
                'severity' => $severity,
                'description' => $description,
            ],
            context: array_merge([
                'category' => 'security',
                'severity' => $severity,
            ], $context)
        );
    }

    /**
     * Calculate changes between old and new data.
     */
    private function calculateChanges(array $oldData, array $newData): array
    {
        $changes = [
            'old' => [],
            'new' => [],
        ];

        // Find modified and new fields
        foreach ($newData as $key => $newValue) {
            if (!array_key_exists($key, $oldData)) {
                $changes['new'][$key] = $newValue;
            } elseif ($oldData[$key] !== $newValue) {
                $changes['old'][$key] = $oldData[$key];
                $changes['new'][$key] = $newValue;
            }
        }

        // Find removed fields
        foreach ($oldData as $key => $oldValue) {
            if (!array_key_exists($key, $newData)) {
                $changes['old'][$key] = $oldValue;
                $changes['new'][$key] = null;
            }
        }

        return $changes;
    }

    /**
     * Get default context from current request.
     */
    private function getDefaultContext(): array
    {
        $context = [
            'timestamp' => microtime(true),
        ];

        if (Yii::$app->has('request') && Yii::$app->request instanceof Request) {
            $request = Yii::$app->request;
            $context['ip'] = $request->getUserIP();
            $context['user_agent'] = $request->getUserAgent();
            $context['url'] = $request->getUrl();
            $context['method'] = $request->getMethod();
        }

        if (PHP_SAPI === 'cli') {
            $context['cli'] = true;
            $context['script'] = $_SERVER['PHP_SELF'] ?? null;
        }

        return $context;
    }

    /**
     * Get current authenticated user ID.
     */
    private function getCurrentUserId(): ?int
    {
        $currentUser = $this->userWebService->getCurrent();

        return $currentUser?->getId();
    }

    private function log(
        string $action,
        ?int $userId = null,
        ?string $entityType = null,
        int|string|null $entityId = null,
        array $changes = [],
        array $context = []
    ): void {
        if (!$this->enabled) {
            return;
        }

        $entry = [
            'action' => $action,
            'user_id' => $userId ?? $this->getCurrentUserId(),
            'entity_type' => $entityType,
            'entity_id' => null !== $entityId ? (string)$entityId : null,
            'changes' => $changes,
            'context' => array_merge($this->getDefaultContext(), $context),
            'timestamp' => time(),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->loggerFileService->info($entry, 'audit');
    }
}
