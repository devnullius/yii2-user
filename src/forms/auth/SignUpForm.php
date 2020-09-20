<?php
declare(strict_types=1);

namespace devnullius\user\forms\auth;

use devnullius\user\entities\User;
use yii\base\Model;

/**
 * SignUp form
 */
final class SignUpForm extends Model
{
    public $username;
    public $email;
    public $phone;
    public $password;
    public $passwordRepeat;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['passwordRepeat', 'compare', 'compareAttribute' => 'password'],

            ['phone', 'required'],
            ['phone', 'integer'],
        ];
    }
}
