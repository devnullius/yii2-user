<?php
declare(strict_types=1);

namespace devnullius\user\helpers;

use devnullius\user\entities\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class UserHelper
{
    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusList(): array
    {
        return [
            User::STATUS_WAIT => Yii::t('core', 'Wait'),
            User::STATUS_ACTIVE => Yii::t('core', 'Active'),
        ];
    }

    public static function statusLabel($status): string
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

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }
}
