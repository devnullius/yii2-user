<?php

use devnullius\user\Module;
use yii\db\Migration;

class m130524_201450_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $userTableWildcardWrapped = '{{%' . Module::getUserTableName() . '}}';

        $this->createTable($userTableWildcardWrapped, [
            'id' => $this->bigPrimaryKey(),
            'created_by' => $this->bigInteger()
                ->notNull()
                ->defaultValue(0)
                ->comment('Modifier id of create, if 0 created from db, if -1 not registered user.'),
            'updated_by' => $this->bigInteger()
                ->notNull()
                ->defaultValue(0)
                ->comment('Modifier id of update, if 0 created from db, if -1 not registered user.'),
            'created_at' => $this->bigInteger()->notNull()->comment('Unix timestamp of create date.'),
            'updated_at' => $this->bigInteger()->notNull()->comment('Unix timestamp of update date.'),
            'modifier' => $this->string()->notNull()->defaultValue('user')->comment('Operation performer entity name.'),
            'deleted' => $this->boolean()->defaultValue(false)->comment('If true row softly deleted, only marker.'),

            'username' => $this->string()->null(),
            'email' => $this->string()->null(),
            'phone' => $this->string()->null(),
            'password_hash' => $this->string()->null(),

            'email_confirm_token' => $this->string()->null(),
            'auth_key' => $this->string(32)->null(),
            'password_reset_token' => $this->string()->null(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),

        ], $tableOptions);

        $this->createIndex('{{%idx-' . Module::getUserTableName() . '-username}}', $userTableWildcardWrapped, 'username', true);
        $this->createIndex('{{%idx-' . Module::getUserTableName() . '-email}}', $userTableWildcardWrapped, 'email', true);
        $this->createIndex('{{%idx-' . Module::getUserTableName() . '-phone}}', $userTableWildcardWrapped, 'phone', true);
        $this->createIndex('{{%idx-' . Module::getUserTableName() . '-email_confirm_token}}', $userTableWildcardWrapped, 'email_confirm_token', true);
        $this->createIndex('{{%idx-' . Module::getUserTableName() . '-password_reset_token}}', $userTableWildcardWrapped, 'password_reset_token', true);

        $this->addColumn($userTableWildcardWrapped, 'verification_token', $this->string()->defaultValue(null));
    }

    public function safeDown()
    {
        $this->dropTable('{{%' . Module::getUserTableName() . '}}');
    }
}
