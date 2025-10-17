<?php

declare(strict_types=1);

use app\modules\user\dictionaries\UserPermissionDictionary;
use yii\base\Exception;
use yii\db\Migration;
use yii\rbac\ManagerInterface;
use yii\rbac\Role;

/**
 * Migration for creating user roles and their hierarchy.
 * Adds `admin` and `user` roles and sets up inheritance.
 */
class m251001_130000_create_users_roles extends Migration
{
    /**
     * Creates `admin` and `user` roles and establishes their hierarchy.
     *
     * @throws Exception if an error occurs during role creation
     */
    public function safeUp(): void
    {
        /** @var ManagerInterface $authManager */
        $authManager = Yii::$app->authManager;

        $adminRole = $authManager->createRole(UserPermissionDictionary::ROLE_ADMIN);
        $adminRole->description = 'Administrator';

        $authManager->add($adminRole);

        $userRole = $authManager->createRole(UserPermissionDictionary::ROLE_USER);
        $userRole->description = 'User';

        $authManager->add($userRole);

        $authManager->addChild($adminRole, $userRole);
    }

    /**
     * Removes `admin` and `user` roles and their hierarchy.
     */
    public function safeDown(): void
    {
        /** @var ManagerInterface $authManager */
        $authManager = Yii::$app->authManager;

        /** @var Role $userRole */
        $userRole = $authManager->getRole(UserPermissionDictionary::ROLE_USER);
        $authManager->remove($userRole);

        /** @var Role $adminRole */
        $adminRole = $authManager->getRole(UserPermissionDictionary::ROLE_ADMIN);
        $authManager->remove($adminRole);
    }
}
