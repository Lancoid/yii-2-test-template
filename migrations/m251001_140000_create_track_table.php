<?php

declare(strict_types=1);

use yii\db\Migration;

class m251001_140000_create_track_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('track', [
            'id' => $this->bigPrimaryKey(),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'number' => $this->string()->notNull(),
            'status' => $this->string()->notNull(),
        ]);

        $this->createIndex('UK__TRACK__NUMBER', 'track', 'number', true);
        $this->createIndex('IDX__TRACK__STATUS', 'track', 'status');
    }

    public function safeDown(): void
    {
        $this->dropTable('track');
    }
}
