<?php

declare(strict_types=1);

use yii\db\Connection;
use yii\db\pgsql\Schema;

$host = getenv('SQL_HOST');
$port = getenv('SQL_INTERNAL_PORT');
$db = YII_ENV_TEST ? getenv('SQL_DB_AUTO') : getenv('SQL_DB_MAIN');
$user = getenv('SQL_USERNAME');
$pass = getenv('SQL_PASSWORD');

return [
    'class' => Connection::class,
    'dsn' => sprintf('pgsql:host=%s;port=%s;dbname=%s', $host, $port, $db),
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
