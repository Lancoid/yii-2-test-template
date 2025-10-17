<?php

declare(strict_types=1);

namespace app\modules\core\services\audit;

use app\modules\core\services\logger\LoggerFileServiceInterface;
use app\modules\user\services\web\UserWebServiceInterface;
use Random\RandomException;
use Throwable;
use Yii;
use yii\web\Request;

readonly class AuditLogService implements AuditLogServiceInterface
{
    public function __construct(
        private LoggerFileServiceInterface $loggerFileService,
        private UserWebServiceInterface $userWebService,
        private bool $enabled = true,
    ) {}

    public function logAuth(string $username, bool $success, ?string $reason = null, array $context = []): void
    {
        if (!$this->enabled) {
            return;
        }

        $action = $success ? 'auth.login.success' : 'auth.login.failed';

        $this->writeLogEntry(
            $this->createEntry(
                action: $action,
                changes: [
                    'username' => $username,
                    'success' => $success,
                    'reason' => $reason,
                ],
                context: array_merge(['category' => 'authentication'], $context)
            )
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

        $this->writeLogEntry(
            $this->createEntry(
                action: sprintf('%s.%s', $entityType, $action),
                userId: $userId,
                entityType: $entityType,
                entityId: $entityId,
                context: array_merge(['category' => 'access'], $context)
            )
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

        $this->writeLogEntry(
            $this->createEntry(
                action: sprintf('%s.%s', $entityType, $action),
                userId: $userId,
                entityType: $entityType,
                entityId: $entityId,
                changes: $changes,
                context: array_merge(['category' => 'modification'], $context)
            )
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

        $this->writeLogEntry(
            $this->createEntry(
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
            )
        );
    }

    /**
     * Calculates the changes between old and new data.
     *
     * @param array $oldData the original data
     * @param array $newData the updated data
     *
     * @return array the changes as arrays of old and new values
     */
    private function calculateChanges(array $oldData, array $newData): array
    {
        $changes = [
            'old' => [],
            'new' => [],
        ];

        foreach ($newData as $key => $newValue) {
            if (!array_key_exists($key, $oldData)) {
                $changes['new'][$key] = $newValue;
            } elseif ($oldData[$key] !== $newValue) {
                $changes['old'][$key] = $oldData[$key];
                $changes['new'][$key] = $newValue;
            }
        }

        foreach ($oldData as $key => $oldValue) {
            if (!array_key_exists($key, $newData)) {
                $changes['old'][$key] = $oldValue;
                $changes['new'][$key] = null;
            }
        }

        return $changes;
    }

    /**
     * Retrieves the request context.
     *
     * @return array The context information (timestamp, IP, user agent, etc.).
     */
    private function getDefaultContext(): array
    {
        $context = [
            'timestamp' => microtime(true),
        ];

        try {
            if (Yii::$app->has('request') && Yii::$app->request instanceof Request) {
                $request = Yii::$app->request;
                $context['ip'] = $request->getUserIP();
                $context['user_agent'] = $request->getUserAgent();
                $context['url'] = $request->getUrl();
                $context['method'] = $request->getMethod();
            }
        } catch (Throwable $e) {
            $context['request_error'] = $e->getMessage();
        }

        if (PHP_SAPI === 'cli') {
            $context['cli'] = true;
            $context['script'] = $_SERVER['PHP_SELF'] ?? null;
        }

        return $context;
    }

    /**
     * Gets the current user ID.
     *
     * @return null|int the user ID or null if not available
     *
     * @throws Throwable
     */
    private function getCurrentUserId(): ?int
    {
        $currentUser = $this->userWebService->getCurrent();

        return $currentUser?->getId();
    }

    /**
     * Creates an audit entry.
     *
     * @param string $action the action name
     * @param null|int $userId the user ID
     * @param null|string $entityType the entity type
     * @param null|int|string $entityId the entity ID
     * @param array $changes the changes made
     * @param array $context additional context
     *
     * @return array the audit entry
     *
     * @throws Throwable
     */
    private function createEntry(
        string $action,
        ?int $userId = null,
        ?string $entityType = null,
        int|string|null $entityId = null,
        array $changes = [],
        array $context = []
    ): array {
        return [
            'uuid' => $this->generateUuid(),
            'action' => $action,
            'user_id' => $userId ?? $this->getCurrentUserId(),
            'entity_type' => $entityType,
            'entity_id' => null !== $entityId ? (string)$entityId : null,
            'changes' => $changes,
            'context' => array_merge($this->getDefaultContext(), $context),
            'timestamp' => time(),
            'created_at' => date('c'), // ISO8601
        ];
    }

    /**
     * Writes an audit entry to the log.
     *
     * @param array $entry the audit entry
     */
    private function writeLogEntry(array $entry): void
    {
        $this->loggerFileService->info($entry, 'audit');
    }

    /**
     * Generates a UUID v4.
     *
     * @return string the generated UUID
     *
     * @throws RandomException
     */
    private function generateUuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0F) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3F) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
