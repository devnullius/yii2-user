<?php
declare(strict_types=1);

namespace devnullius\user\helpers;

use devnullius\helper\helpers\CoreHelper;
use devnullius\user\entities\User;
use devnullius\user\Module;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class UserHelper extends CoreHelper
{
    public static function statusName(int $status): string
    {
        try {
            return ArrayHelper::getValue(self::statusList(), $status);
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);

            return $e->getMessage();
        }
    }

    public static function statusList(): array
    {
        return [
            User::STATUS_WAIT => Module::t('helpers', 'Wait'),
            User::STATUS_ACTIVE => Module::t('helpers', 'Active'),
        ];
    }

    public static function statusLabel(int $status): string
    {
        switch ($status) {
            case User::STATUS_WAIT:
                $class = 'label label-default';
                break;
            case User::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        try {
            return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
                'class' => $class,
            ]);
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);

            return $e->getMessage();
        }
    }
}
