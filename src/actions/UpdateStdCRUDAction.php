<?php
declare(strict_types=1);

namespace devnullius\user\actions;

use Exception;
use Yii;
use yii\base\Action;
use yii\base\ErrorException;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;

abstract class UpdateStdCRUDAction extends Action
{
    use StdCRUDActionsTrait;

    public $view = 'update';
    public $redirectView = 'view';

    /**
     * @param int $id
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function run(int $id)
    {
        /** @var ActiveRecord $model */
        $model = $this->findModelById($id);

        if ($model->load(Yii::$app->request->post())) {
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {

                if (!$model->save()) {
                    throw new ErrorException(Yii::t('basic', 'Item save error. {errors}', ['errors' => Json::encode($model->getErrors())]));
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', Yii::t('basic', 'Item successfully updated.'));
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
