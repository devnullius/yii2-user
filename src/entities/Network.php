<?php
declare(strict_types=1);

namespace devnullius\user\entities;

use devnullius\user\Module;
use Webmozart\Assert\Assert;
use yii\db\ActiveRecord;

/**
 * @property integer $user_id
 * @property string  $identity
 * @property string  $network
 * @property string  $id [integer]
 */
final class Network extends ActiveRecord
{
    public static function create(string $network, string $identity): self
    {
        Assert::notEmpty($network);
        Assert::notEmpty($identity);

        $item = new static();
        $item->network = $network;
        $item->identity = $identity;

        return $item;
    }

    public static function tableName(): string
    {
        return '{{%' . Module::getUserNetworkTableName() . '}}';
    }

    public function isFor(string $network, string $identity): bool
    {
        return $this->network === $network && $this->identity === $identity;
    }
}
