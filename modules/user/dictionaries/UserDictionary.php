<?php

declare(strict_types=1);

namespace app\modules\user\dictionaries;

class UserDictionary
{
    public const int STATUS_ACTIVE = 1;

    public const int STATUS_BLOCKED = 0;

    /**
     * @return array<int, string>
     */
    public static function getStatusesList(): array
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_BLOCKED => 'Заблокирован',
        ];
    }
}
