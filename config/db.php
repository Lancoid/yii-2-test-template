<?php

declare(strict_types=1);

use yii\db\Connection;
use yii\db\pgsql\Schema;

$dsn = getenv('DB_DSN');
$user = getenv('SQL_USERNAME');
$pass = getenv('SQL_PASSWORD');

return [
    'class' => Connection::class,
    'dsn' => $dsn,
    'username' => $user,
    'password' => $pass,
    'charset' => 'utf8',
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3 * 60 * 60 * 24,
    'schemaCache' => 'cache',
    'enableLogging' => false,
    'enableProfiling' => false,
    'schemaMap' => [
        'pgsql' => [
            'class' => Schema::class,
            'defaultSchema' => 'public',
        ],
    ],
];
