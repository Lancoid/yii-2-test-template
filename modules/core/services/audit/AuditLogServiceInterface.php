<?php

declare(strict_types=1);

namespace app\modules\core\services\audit;

/**
 * Audit logging service interface.
 *
 * Tracks user actions, data changes, and system events for compliance and security.
 */
interface AuditLogServiceInterface
{
    /**
     * Log authentication attempt.
     *
     * @param string $username Username or email
     * @param bool $success Whether authentication succeeded
     * @param null|string $reason Failure reason if unsuccessful
     * @param array $context Additional context
     */
    public function logAuth(string $username, bool $success, ?string $reason = null, array $context = []): void;

    /**
     * Log data access.
     *
     * @param string $entityType Entity type accessed
     * @param int|string $entityId Entity ID accessed
     * @param string $action Action performed (view, list, export)
     * @param null|int $userId User who accessed the data
     * @param array $context Additional context
     */
    public function logAccess(
        string $entityType,
        int|string $entityId,
        string $action,
        ?int $userId = null,
        array $context = []
    ): void;

    /**
     * Log data modification.
     *
     * @param string $entityType Entity type modified
     * @param int|string $entityId Entity ID modified
     * @param string $action Action performed (create, update, delete)
     * @param array $oldData Data before modification
     * @param array $newData Data after modification
     * @param null|int $userId User who modified the data
     * @param array $context Additional context
     */
    public function logModification(
        string $entityType,
        int|string $entityId,
        string $action,
        array $oldData,
        array $newData,
        ?int $userId = null,
        array $context = []
    ): void;

    /**
     * Log security event.
     *
     * @param string $event Event type (e.g., 'permission_denied', 'suspicious_activity')
     * @param string $severity Severity level (low, medium, high, critical)
     * @param string $description Event description
     * @param null|int $userId Related user ID
     * @param array $context Additional context
     */
    public function logSecurity(
        string $event,
        string $severity,
        string $description,
        ?int $userId = null,
        array $context = []
    ): void;
}
