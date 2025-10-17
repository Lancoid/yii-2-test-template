<?php

declare(strict_types=1);

namespace app\modules\user\dictionaries;

/**
 * Dictionary for user permission roles.
 * Provides constants and a list of available user roles.
 */
class UserPermissionDictionary
{
    /**
     * Administrator role.
     */
    public const string ROLE_ADMIN = 'admin';

    /**
     * Regular user role.
     */
    public const string ROLE_USER = 'user';

    /**
     * Returns an associative array of available roles.
     * Key is the role identifier, value is the role name.*.
     *
     * @return array<string, string> list of role identifiers mapped to role names
     */
    public static function getRolesList(): array
    {
        return [
            self::ROLE_ADMIN => 'Админ',
            self::ROLE_USER => 'Пользователь',
        ];
    }
}
