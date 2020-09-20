<?php
declare(strict_types=1);

namespace devnullius\user\forms\auth;

use yii\base\Model;

final class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    public function rules(): array
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
        ];
    }
}
