<?php

declare(strict_types=1);

namespace app\modules\core\services\audit;

use Throwable;

/**
 * Interface for the audit logging service.
 *
 * Records user actions, data changes, and system events for compliance and security monitoring.
 */
interface AuditLogServiceInterface
{
    /**
     * Logs an authentication attempt.
     *
     * @param string $username username or email used for authentication
     * @param bool $success indicates whether authentication was successful
     * @param null|string $reason reason for failure, if authentication was unsuccessful
     * @param array $context additional context information
     *
     * @throws Throwable
     */
    public function logAuth(string $username, bool $success, ?string $reason = null, array $context = []): void;

    /**
     * Logs access to an entity.
     *
     * @param string $entityType type of the accessed entity
     * @param int|string $entityId identifier of the accessed entity
     * @param string $action Action performed (e.g., view, list, export).
     * @param null|int $userId ID of the user who accessed the entity
     * @param array $context additional context information
     *
     * @throws Throwable
     */
    public function logAccess(
        string $entityType,
        int|string $entityId,
        string $action,
        ?int $userId = null,
        array $context = []
    ): void;

    /**
     * Logs modification of an entity.
     *
     * @param string $entityType type of the modified entity
     * @param int|string $entityId identifier of the modified entity
     * @param string $action Action performed (e.g., create, update, delete).
     * @param array $oldData data before modification
     * @param array $newData data after modification
     * @param null|int $userId ID of the user who modified the entity
     * @param array $context additional context information
     *
     * @throws Throwable
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
     * Logs a security-related event.
     *
     * @param string $event Type of the security event (e.g., permission_denied, suspicious_activity).
     * @param string $severity severity level (low, medium, high, critical)
     * @param string $description description of the event
     * @param null|int $userId ID of the related user
     * @param array $context additional context information
     *
     * @throws Throwable
     */
    public function logSecurity(
        string $event,
        string $severity,
        string $description,
        ?int $userId = null,
        array $context = []
    ): void;
}
