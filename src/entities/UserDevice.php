<?php
declare(strict_types=1);

namespace devnullius\user\entities;

use devnullius\user\Module;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class UserDeviceStore
 *
 * @package core\entities\User
 * @property int    $id          [bigint]
 * @property int    $created_by  [bigint]  Modifier id of create, if 0 created from db, if -1 not registered user.
 * @property int    $updated_by  [bigint]  Modifier id of update, if 0 created from db, if -1 not registered user.
 * @property int    $created_at  [bigint]  Unix timestamp of create date.
 * @property int    $updated_at  [bigint]  Unix timestamp of update date.
 * @property string $modifier    [varchar(255)]  Operation performer entity name.
 * @property bool   $deleted     [boolean]  If true row softly deleted, only marker.
 * @property int    $user_id     [bigint]  System User ID
 * @property string $firebase_id [varchar(255)]  Firebase User ID - Device ID
 * @property User   $user
 */
final class UserDevice extends ActiveRecord
{
    public static function create(int $userId, string $fireBaseId): self
    {
        $static = new static();
        $static->user_id = $userId;
        $static->firebase_id = $fireBaseId;

        return $static;
    }

    public static function tableName(): string
    {
        return '{{%' . Module::getUserDeviceTableName() . '}}';
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'defaultValue' => -1,
            ],
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
}
