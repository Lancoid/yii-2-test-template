<?php

declare(strict_types=1);

namespace app\modules\track\dictionaries;

class TrackDictionary
{
    public const string STATUS_NEW = 'new';

    public const string STATUS_IN_PROGRESS = 'in_progress';

    public const string STATUS_COMPLETED = 'completed';

    public const string STATUS_FAILED = 'failed';

    public const string STATUS_CANCELED = 'canceled';

    /**
     * @return array<string>
     */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_NEW,
            self::STATUS_IN_PROGRESS,
            self::STATUS_COMPLETED,
            self::STATUS_FAILED,
            self::STATUS_CANCELED,
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function getStatusListDescription(): array
    {
        return [
            self::STATUS_NEW => 'Новая',
            self::STATUS_IN_PROGRESS => 'В обработке',
            self::STATUS_COMPLETED => 'Завершено',
            self::STATUS_FAILED => 'Ошибка',
            self::STATUS_CANCELED => 'Отмена',
        ];
    }
}
