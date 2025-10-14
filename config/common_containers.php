<?php

declare(strict_types=1);

/**
 * @noinspection StaticClosureCanBeUsedInspection
 */

use app\modules\core\services\audit\AuditLogService;
use app\modules\core\services\audit\AuditLogServiceInterface;
use app\modules\core\services\authManager\AuthManagerService;
use app\modules\core\services\authManager\AuthManagerServiceInterface;
use app\modules\core\services\cache\CacheService;
use app\modules\core\services\cache\CacheServiceInterface;
use app\modules\core\services\database\DatabaseTransactionService;
use app\modules\core\services\database\DatabaseTransactionServiceInterface;
use app\modules\core\services\logger\LoggerFileService;
use app\modules\core\services\logger\LoggerFileServiceInterface;
use app\modules\core\services\metrics\MetricsService;
use app\modules\core\services\metrics\MetricsServiceInterface;
use app\modules\core\services\metrics\storage\CacheMetricsStorage;
use app\modules\core\services\metrics\storage\FileMetricsStorage;
use app\modules\core\services\metrics\storage\MetricsStorageInterface;
use app\modules\core\services\sentry\SentryService;
use app\modules\core\services\sentry\SentryServiceInterface;
use app\modules\track\dataProviders\TrackDataProvider;
use app\modules\track\dataProviders\TrackDataProviderInterface;
use app\modules\track\repositories\TrackRepository;
use app\modules\track\repositories\TrackRepositoryInterface;
use app\modules\track\services\create\TrackCreateService;
use app\modules\track\services\create\TrackCreateServiceInterface;
use app\modules\track\services\delete\TrackDeleteService;
use app\modules\track\services\delete\TrackDeleteServiceInterface;
use app\modules\track\services\update\TrackUpdateService;
use app\modules\track\services\update\TrackUpdateServiceInterface;
use app\modules\user\dataProviders\UserDataProvider;
use app\modules\user\dataProviders\UserDataProviderInterface;
use app\modules\user\repositories\UserRepository;
use app\modules\user\repositories\UserRepositoryInterface;
use app\modules\user\services\create\UserCreateService;
use app\modules\user\services\create\UserCreateServiceInterface;
use app\modules\user\services\login\UserLoginService;
use app\modules\user\services\login\UserLoginServiceInterface;
use app\modules\user\services\update\UserUpdateService;
use app\modules\user\services\update\UserUpdateServiceInterface;
use app\modules\user\services\web\UserWebService;
use app\modules\user\services\web\UserWebServiceInterface;
use yii\caching\CacheInterface;
use yii\db\Connection;
use yii\di\Instance;
use yii\log\Logger;
use yii\rbac\DbManager as YiiRbacDbManager;
use yii\redis\Cache;

/** @var array<string,mixed> $params */
$params = require __DIR__ . '/params.php';

return [
    'singletons' => [
        /* DATABASE */
        Connection::class => require __DIR__ . '/db.php',
        DatabaseTransactionServiceInterface::class => DatabaseTransactionService::class,

        /* CACHE */
        CacheInterface::class => [
            'class' => Cache::class,
            'keyPrefix' => getenv('REDIS_PREFIX'),
            'defaultDuration' => (int)getenv('REDIS_DEFAULT_DURATION_CACHE'),
            'redis' => [
                'hostname' => getenv('REDIS_HOST'),
                'port' => getenv('REDIS_PORT'),
                'database' => getenv('REDIS_DATABASE'),
            ],
        ],
        CacheServiceInterface::class => CacheService::class,

        /* SENTRY */
        SentryServiceInterface::class => [
            'class' => SentryService::class,
            'dsn' => getenv('SENTRY_DSN'),
            'enabled' => (bool)getenv('SENTRY_DSN'),
            'clientOptions' => [
                'release' => $params['version'],
                'environment' => (string)getenv('SENTRY_ENV'),
            ],
            'context' => true,
        ],

        /* AUTH MANAGER */
        YiiRbacDbManager::class => [
            'class' => YiiRbacDbManager::class,
            'cache' => CacheInterface::class,
            'itemTable' => 'users_auth_item',
            'itemChildTable' => 'users_auth_item_child',
            'assignmentTable' => 'users_auth_assignment',
            'ruleTable' => 'users_auth_rule',
        ],
        AuthManagerServiceInterface::class => [
            ['class' => AuthManagerService::class],
            [Instance::of(YiiRbacDbManager::class)],
        ],

        /* LOGGER */
        Logger::class => ['profilingAware' => true],
        LoggerFileServiceInterface::class => LoggerFileService::class,
        AuditLogServiceInterface::class => [
            ['class' => AuditLogService::class],
            [
                getenv('AUDIT_ENABLED'),
                Instance::of(LoggerFileServiceInterface::class),
            ],
        ],

        /* METRICS */
        MetricsStorageInterface::class => [
            ['class' => YII_ENV_PROD
                ? CacheMetricsStorage::class
                : FileMetricsStorage::class,
            ],
            YII_ENV_PROD
            ? [
                Instance::of(CacheServiceInterface::class),
                getenv('METRICS_CACHE_TTL'),
            ]
                : [getenv('METRICS_STORAGE_PATH')],
        ],
        MetricsServiceInterface::class => [
            ['class' => MetricsService::class],
            [
                Instance::of(MetricsStorageInterface::class),
                'false' !== getenv('METRICS_ENABLED'),
            ],
        ],

        /* USER */
        UserCreateServiceInterface::class => UserCreateService::class,
        UserDataProviderInterface::class => UserDataProvider::class,
        UserLoginServiceInterface::class => UserLoginService::class,
        UserRepositoryInterface::class => UserRepository::class,
        UserUpdateServiceInterface::class => UserUpdateService::class,
        UserWebServiceInterface::class => UserWebService::class,

        /* TRACK */
        TrackCreateServiceInterface::class => TrackCreateService::class,
        TrackDataProviderInterface::class => TrackDataProvider::class,
        TrackDeleteServiceInterface::class => TrackDeleteService::class,
        TrackRepositoryInterface::class => TrackRepository::class,
        TrackUpdateServiceInterface::class => TrackUpdateService::class,
    ],
];
