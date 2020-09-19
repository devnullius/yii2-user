<?php

use devnullius\user\Module;
use yii\db\Expression;
use yii\db\Migration;

class m170531_203800_change_auth_assignments_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        if ($tableOptions !== null) {
            $this->alterColumn('{{%auth_assignments}}', 'user_id', $this->integer()->null());
        } else {
            $this->execute(new Expression('ALTER TABLE auth_assignments ALTER COLUMN user_id TYPE bigint USING user_id::bigint'));
        }

        $this->createIndex('{{%idx-auth_assignments-user_id}}', '{{%auth_assignments}}', 'user_id');

        $this->addForeignKey(
            '{{%fk-auth_assignments-user_id}}',
            '{{%auth_assignments}}',
            'user_id',
            '{{%' . Module::getUserTableName() . '}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-auth_assignments-user_id}}', '{{%auth_assignments}}');

        $this->dropIndex('{{%idx-auth_assignments-user_id}}', '{{%auth_assignments}}');

        $this->alterColumn('{{%auth_assignments}}', 'user_id', $this->string(64)->notNull());
    }
}
