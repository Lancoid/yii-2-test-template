<?php

declare(strict_types=1);
use app\modules\user\dictionaries\UserPermissionDictionary;

return [
    [
        'parent' => UserPermissionDictionary::ROLE_ADMIN,
        'child' => UserPermissionDictionary::ROLE_USER,
    ],
];
