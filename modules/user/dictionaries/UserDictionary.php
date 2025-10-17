<?php

declare(strict_types=1);

namespace app\modules\user\dictionaries;

/**
 * Dictionary for user-related constants and status lists.
 */
class UserDictionary
{
    /**
     * User is active.
     */
    public const int STATUS_ACTIVE = 1;

    /**
     * User is blocked.
     */
    public const int STATUS_BLOCKED = 0;

    /**
     * User agreed to personal data processing.
     */
    public const int AGREEMENT_TO_PROCESSING_PERSONAL_DATA = 1;

    /**
     * Returns the list of user statuses.
     *
     * @return array<int, string> array of status code to status name
     */
    public static function getStatusesList(): array
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_BLOCKED => 'Заблокирован',
        ];
    }
}
