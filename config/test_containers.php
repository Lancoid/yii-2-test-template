<?php

declare(strict_types=1);

/**
 * @noinspection StaticClosureCanBeUsedInspection
 */

use yii\db\Connection;

return [
    'singletons' => [
        /* DATABASE */
        Connection::class => require __DIR__ . '/test_db.php',
    ],
];
