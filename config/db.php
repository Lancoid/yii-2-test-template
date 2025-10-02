<?php

declare(strict_types=1);

use yii\db\Connection;

$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$db = getenv('DB_DATABASE');
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');

return [
    'class' => Connection::class,
    'dsn' => sprintf('mysql:host=%s;port=%s;dbname=%s', $host, $port, $db),
    'username' => $user,
    'password' => $pass,
    'charset' => 'utf8',
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3 * 60 * 60 * 24,
    'schemaCache' => 'cache',
    'enableLogging' => false,
    'enableProfiling' => false,
];
