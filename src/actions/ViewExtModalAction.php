<?php
declare(strict_types=1);

namespace devnullius\user\actions;

class ViewExtModalAction extends ViewExtCRUDAction
{
    public $redirectOnFailRoute = ['index'];
    public $redirectView = ['index'];

//    public function run(int $id)
//    {
//        /**
//         * @var $entity ActiveRecordInterface
//         */
//        $entity = $this->repository->get($id);
//
//        if ($entity->load(Yii::$app->request->post()) && $entity->validate()) {
//
//            try {
//
//                $entity = $this->service->{$this->serviceAction}($entity);
//
    //                Yii::$app->session->setFlash('success', Module::t('basic', 'Item changes successfully saved.'));
    //
//                return $this->controller->redirect([$this->redirectView, 'id' => $entity->id]);
//            } catch (DomainException $e) {
//                Yii::$app->errorHandler->logException($e);
//                Yii::$app->session->setFlash('error', $e->getMessage());
//            }
//
//        }
//
//        return $this->controller->render(
//            $this->view,
//            [
//                'model' => $entity,
//            ]
//        );
//    }
}
