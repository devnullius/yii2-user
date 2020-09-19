<?php

namespace devnullius\user\actions;

use yii\web\Response;

class DeleteAction extends DeleteStdCRUDAction
{
    public function run(int $id): Response
    {
        return parent::run($id);
    }
}
