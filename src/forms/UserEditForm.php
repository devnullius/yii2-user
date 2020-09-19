<?php
declare(strict_types=1);

namespace devnullius\user\forms;

use devnullius\user\entities\User;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

final class UserEditForm extends Model
{
    public $username;
    public $email;
    public $phone;
    public $role;

    public $generateTokens;

    public $_user;

    public function __construct(User $user, $config = [])
    {
        $this->username = $user->username;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $roles = Yii::$app->authManager->getRolesByUser($user->id);
        $this->role = $roles ? reset($roles)->name : null;
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['username', 'email', 'phone', 'role'], 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['phone', 'integer'],
            [['username', 'email', 'phone'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
            ['generateTokens', 'boolean'],
            ['generateTokens', 'default', 'value' => false],
        ];
    }

    public function rolesList(): array
    {
        return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
    }
}
