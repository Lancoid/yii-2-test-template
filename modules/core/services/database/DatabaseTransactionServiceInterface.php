<?php

declare(strict_types=1);

namespace app\modules\core\services\database;

use Closure;
use Throwable;
use yii\db\Exception as YiiDbException;

interface DatabaseTransactionServiceInterface
{
    /**
     * @throws Throwable
     * @throws YiiDbException
     */
    public function handle(Closure $transaction): mixed;
}
