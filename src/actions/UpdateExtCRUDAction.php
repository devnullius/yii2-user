<?php
declare(strict_types=1);

namespace devnullius\user\actions;

use core\interfaces\repository\GetRepository;
use core\interfaces\service\EditService;
use DomainException;
use Yii;
use yii\base\Action;
use yii\base\Model;
use yii\bootstrap4\ActiveForm;
use yii\db\ActiveRecordInterface;
use yii\web\Response;

abstract class UpdateExtCRUDAction extends Action
{
    /**
     * @var Model
     */
    public $form;
    /**
     * @var EditService
     */
    public $service;
    /**
     * @var GetRepository
     */
    public $repository;
    public $view = 'update';
    public $redirectView = 'view';
    public $serviceAction = 'edit';

    public function run(int $id)
    {
        /**
         * @var $entity ActiveRecordInterface
         */
        $entity = $this->repository->get($id);
        $formClass = $this->form;
        $form = new $formClass($entity);

        if (Yii::$app->request->isAjax && $form->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($form);
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {

                $entity = $this->service->{$this->serviceAction}($form);

                Yii::$app->session->setFlash('success', Yii::t('basic', 'Item {uid} successfully updated.', ['uid' => $entity->uid ?? $id]));

                return $this->controller->redirect([$this->redirectView, 'id' => $entity->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }

        }

        return $this->controller->render(
            $this->view,
            [
                'model' => $form,
            ]
        );
    }
}
