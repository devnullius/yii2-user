<?php
declare(strict_types=1);

namespace devnullius\user\actions;

use DomainException;
use Yii;
use yii\base\Model;
use yii\bootstrap4\ActiveForm;
use yii\web\Response;

class CreateExtModalAction extends CreateExtCRUDAction
{
    public $redirectOnFailRoute = ['index'];
    public $redirectView = ['index'];

    public function run()
    {
        /**
         * @var $form Model
         */
        $form = new $this->form();

        if (Yii::$app->request->isAjax && $form->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($form);
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {

                $entity = $this->service->{$this->serviceAction}($form);

                Yii::$app->session->setFlash('success', Yii::t('basic', 'Item {uid} successfully created.', ['uid' => $entity->uid ?? $entity->id]));

                return $this->controller->redirect($this->redirectView);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }

        }

        return $this->controller->redirect($this->redirectOnFailRoute);
    }
}
