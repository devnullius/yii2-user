<?php
declare(strict_types=1);

namespace devnullius\user\forms\auth;

use yii\base\Model;

final class ResetPasswordForm extends Model
{
    public $password;
    public $passwordRepeat;

    public function rules(): array
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['passwordRepeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }
}
