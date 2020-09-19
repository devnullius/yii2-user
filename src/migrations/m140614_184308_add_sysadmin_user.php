<?php

use devnullius\user\Module;
use yii\db\Migration;

class m140614_184308_add_sysadmin_user extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%' . Module::getUserTableName() . '}}', [
            'id' => 1,
            'username' => 'sysadmin',
            'auth_key' => 'B7asUsSVv9qWuUxojpcTOjm6FctcdN7m',
            'password_hash' => '$2y$13$o94CdQ8mfr3F1Ikb3YXjGeSEfRLZdUx3ofOZ2tqRyx6C.LBcon1Ya',
            'password_reset_token' => null,
            'email' => 'admin@3m-life.com',
            'phone' => '37411234000',
            'email_confirm_token' => null,
            'status' => 10,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%' . Module::getUserTableName() . '}}', ['id' => 1]);
    }
}
