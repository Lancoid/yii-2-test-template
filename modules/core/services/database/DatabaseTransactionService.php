<?php

declare(strict_types=1);

namespace app\modules\core\services\database;

use Closure;
use Throwable;
use yii\db\Connection as YiiDbConnection;

class DatabaseTransactionService implements DatabaseTransactionServiceInterface
{
    private YiiDbConnection $yiiDbConnection;

    public function __construct(YiiDbConnection $yiiDbConnection)
    {
        $this->yiiDbConnection = $yiiDbConnection;
    }

    public function handle(Closure $transaction): mixed
    {
        $databaseTransaction = $this->yiiDbConnection->beginTransaction();

        try {
            $result = $transaction();

            $databaseTransaction->commit();
        } catch (Throwable $throwable) {
            $databaseTransaction->rollBack();

            throw $throwable;
        }

        return $result;
    }
}
