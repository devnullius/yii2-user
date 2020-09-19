<?php
declare(strict_types=1);

namespace devnullius\user\actions;

use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;

abstract class IndexStdCRUDAction extends Action
{
    public $view = 'index';
    public $searchModel;

    /**
     * @return string
     */
    public function run()
    {
        /** @var ActiveRecord $searchModel */
        $searchModel = new $this->searchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render(
            $this->view,
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }
}
