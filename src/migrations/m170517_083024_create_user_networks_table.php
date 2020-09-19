<?php

use devnullius\user\Module;
use yii\db\Migration;

class m170517_083024_create_user_networks_table extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%' . Module::getUserNetworkTableName() . '}}', [
            'id' => $this->bigPrimaryKey(),
            'user_id' => $this->bigInteger()->notNull(),
            'identity' => $this->string()->notNull(),
            'network' => $this->string(16)->notNull(),
        ], $tableOptions);

        $this->createIndex(
            '{{%idx-' . Module::getUserNetworkTableName() . '-identity-name}}',
            '{{%' . Module::getUserNetworkTableName() . '}}',
            ['identity', 'network'],
            true
        );

        $this->createIndex(
            '{{%idx-' . Module::getUserNetworkTableName() . '-user_id}}',
            '{{%' . Module::getUserNetworkTableName() . '}}',
            'user_id'
        );

        $this->addForeignKey(
            '{{%fk-' . Module::getUserNetworkTableName() . '-user_id}}',
            '{{%' . Module::getUserNetworkTableName() . '}}',
            'user_id',
            '{{%' . Module::getUserTableName() . '}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%' . Module::getUserNetworkTableName() . '}}');
    }
}
