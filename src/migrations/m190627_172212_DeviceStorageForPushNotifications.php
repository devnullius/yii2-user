<?php

use devnullius\user\Module;
use yii\db\Migration;

class m190627_172212_DeviceStorageForPushNotifications extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%' . Module::getUserDeviceTableName() . '}}', [
            'id' => $this->bigPrimaryKey(),
            'created_by' => $this->bigInteger()->notNull()
                ->defaultValue(0)
                ->comment('Modifier id of create, if 0 created from db, if -1 not registered user.'),
            'updated_by' => $this->bigInteger()->notNull()
                ->defaultValue(0)
                ->comment('Modifier id of update, if 0 created from db, if -1 not registered user.'),
            'created_at' => $this->bigInteger()->notNull()->comment('Unix timestamp of create date.'),
            'updated_at' => $this->bigInteger()->notNull()->comment('Unix timestamp of update date.'),
            'modifier' => $this->string()->notNull()->defaultValue('user')->comment('Operation performer entity name.'),
            'deleted' => $this->boolean()->defaultValue(false)->comment('If true row softly deleted, only marker.'),

            'user_id' => $this->bigInteger()->null()->comment('System User ID'),
            'firebase_id' => $this->string()->null()->comment('Firebase User ID - Device ID'),

        ], $tableOptions);

        $this->createIndex('{{%idx-' . Module::getUserDeviceTableName() . '_firebase_id}}', '{{%' . Module::getUserDeviceTableName() . '}}', 'firebase_id', true);

        $this->addForeignKey(
            'fk-' . Module::getUserDeviceTableName() . '_user_id',
            '{{%' . Module::getUserDeviceTableName() . '}}',
            'user_id',
            '{{%' . Module::getUserTableName() . '}}',
            'id',
            'CASCADE',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-' . Module::getUserDeviceTableName() . '_user_id', '{{%' . Module::getUserDeviceTableName() . '}}');
        $this->dropTable('{{%' . Module::getUserDeviceTableName() . '}}');
    }
}
