<?php

declare(strict_types=1);

namespace app\modules\core\services\database;

use Closure;
use Throwable;

/**
 * Interface for database transaction service.
 * Provides a method to execute code within a database transaction.
 */
interface DatabaseTransactionServiceInterface
{
    /**
     * Executes a closure within a database transaction.
     *
     * @param Closure $transaction The code to execute in transaction
     *
     * @return mixed The result of the closure
     *
     * @throws Throwable If an error occurs during transaction
     */
    public function handle(Closure $transaction): mixed;
}
