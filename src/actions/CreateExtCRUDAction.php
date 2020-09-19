<?php
declare(strict_types=1);

namespace devnullius\user\actions;

use core\interfaces\service\CreateService;
use DomainException;
use Yii;
use yii\base\Action;
use yii\base\Model;

abstract class CreateExtCRUDAction extends Action
{
    /**
     * @var Model
     */
    public $form;
    /**
     * @var CreateService
     */
    public $service;
    public $view = 'create';
    public $redirectView = 'view';
    public $serviceAction = 'create';

    public function run()
    {
        /**
         * @var $form Model
         */
        $form = new $this->form();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {

                $user = $this->service->{$this->serviceAction}($form);

                Yii::$app->session->setFlash('success', Yii::t('basic', 'Item successfully created.'));

                return $this->controller->redirect([$this->redirectView, 'id' => $user->id]);
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
