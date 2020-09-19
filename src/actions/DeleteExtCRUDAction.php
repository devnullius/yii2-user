<?php
declare(strict_types=1);

namespace devnullius\user\actions;

use core\interfaces\service\RemoveService;
use devnullius\user\Module;
use Exception;
use Yii;
use yii\base\Action;
use yii\web\Response;

abstract class DeleteExtCRUDAction extends Action
{
    /**
     * @var RemoveService
     */
    public $service;
    public $redirectPath = 'index';

    public function run(int $id): Response
    {

        try {
            $entity = null;
            $this->service->remove($id, $entity);
            Yii::$app->session->setFlash('warning', Module::t('basic', 'Item {uid} successfully deleted.', ['uid' => $entity->uid ?? $id]));
        } catch (Exception $e) {
            Yii::$app->session->setFlash('danger', $e->getMessage());
        }

        return $this->controller->redirect([$this->redirectPath]);
    }
}
