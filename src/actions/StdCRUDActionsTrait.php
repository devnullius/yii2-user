<?php
declare(strict_types=1);

namespace devnullius\user\actions;

use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

trait StdCRUDActionsTrait
{
    //public $id;
    public $modelClass;

    /**
     * @param int $id
     *
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    public function findModelById(int $id): ActiveRecord
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        if (null !== ($model = $modelClass::findOne(['id' => $id, 'deleted' => false]))) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
