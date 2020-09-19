<?php
declare(strict_types=1);

namespace devnullius\user\actions;

use yii\base\Action;

abstract class ViewStdCRUDAction extends Action
{
    use StdCRUDActionsTrait;

    public $view = 'view';

    public function run(int $id)
    {
        return $this->controller->render(
            $this->view,
            [
                'model' => $this->findModelById($id),
            ]
        );
    }
}
