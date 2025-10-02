<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Dotenv\Repository\Adapter\EnvConstAdapter;
use Dotenv\Repository\Adapter\PutenvAdapter;
use Dotenv\Repository\RepositoryBuilder;

if (file_exists(__DIR__ . '/../.env')) {
    $repository = RepositoryBuilder::createWithNoAdapters()
        ->addAdapter(EnvConstAdapter::class)
        ->addWriter(PutenvAdapter::class)
        ->make();

    Dotenv::create($repository, __DIR__ . '/..')->load();
}

defined('YII_DEBUG') || define('YII_DEBUG', getenv('YII_DEBUG') ?: false);
defined('YII_ENV') || define('YII_ENV', getenv('YII_ENV') ?: 'prod');
