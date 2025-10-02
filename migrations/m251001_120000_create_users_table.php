<?php

declare(strict_types=1);

use yii\db\Migration;

class m251001_120000_create_users_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('users', [
            'id' => $this->bigPrimaryKey(),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'username' => $this->string()->notNull(),
            'password_hash' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'access_token' => $this->string(),
            'email' => $this->string()->notNull(),
            'phone' => $this->string(32)->notNull(),
            'status' => $this->tinyInteger(1)->notNull(),
        ]);

        $this->createIndex('UK__USERS__ACCESS_TOKEN', 'users', 'access_token', true);
        $this->createIndex('UK__USERS__EMAIL', 'users', 'email', true);
    }

    public function safeDown(): void
    {
        $this->dropTable('users');
    }
}
