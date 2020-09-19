<?php
declare(strict_types=1);

namespace devnullius\user\actions;

use common\components\UIDGenerator;
use devnullius\user\Module;
use Exception;
use Yii;
use yii\base\Action;
use yii\base\ErrorException;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\Response;

abstract class CreateStdCRUDAction extends Action
{
    public $modelClass;
    public $view = 'create';
    public $redirectView = 'view';

    /**
     * @return string|Response
     * @throws ErrorException
     */
    public function run()
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClass();
        if ($model->hasAttribute('uid')) {
            $model->uid = (string)UIDGenerator::init($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                if (!$model->save()) {
                    throw new ErrorException(Module::t('basic', 'Item save error. {errors}', ['errors' => Json::encode($model->getErrors())]));
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', Module::t('basic', 'Item successfully created.'));
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('danger', $e->getMessage());
            }

            return $this->controller->redirect([$this->redirectView, 'id' => $model->id]);
        }

        return $this->controller->render(
            $this->view,
            [
                'model' => $model,
            ]
        );
    }
}
