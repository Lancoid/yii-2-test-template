<?php

declare(strict_types=1);

use app\tests\fixtures\UserAuthAssignmentFixture;
use app\tests\fixtures\UserAuthItemChildFixture;
use app\tests\fixtures\UserAuthItemFixture;
use app\tests\fixtures\UserAuthRuleFixture;
use app\tests\fixtures\UserFixture;

$dataDirectory = __DIR__ . '/data/';

return [
    UserFixture::class => $dataDirectory . 'user.php',
    UserAuthAssignmentFixture::class => $dataDirectory . 'user_auth_assignment.php',
    UserAuthItemChildFixture::class => $dataDirectory . 'user_auth_item_child.php',
    UserAuthItemFixture::class => $dataDirectory . 'user_auth_item.php',
    UserAuthRuleFixture::class => $dataDirectory . 'user_auth_rule.php',
];
