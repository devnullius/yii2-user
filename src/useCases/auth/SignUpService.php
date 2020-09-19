<?php
declare(strict_types=1);

namespace devnullius\user\useCases\auth;

use devnullius\queue\addon\wrappers\transaction\TransactionWrapper;
use devnullius\user\entities\User;
use devnullius\user\forms\auth\SignUpForm;
use devnullius\user\repositories\UserRepository;
use devnullius\user\services\RoleManager;
use DomainException;
use Exception;
use Yii;

final class SignUpService
{
    private UserRepository $users;
    private RoleManager $roles;
    private TransactionWrapper $transaction;

    public function __construct(
        UserRepository $users,
        RoleManager $roles,
        TransactionWrapper $transaction
    ) {
        $this->users = $users;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }

    public function signUp(SignUpForm $form): void
    {
        $user = User::requestSignup(
            $form->username,
            $form->email,
            $form->phone,
            $form->password
        );
        try {
            $this->transaction->wrap(function () use ($user) {
                $this->users->save($user);
                $this->roles->assign($user->id, 'user');
            });
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
    }

    public function confirm(string $token): void
    {
        if (empty($token)) {
            throw new DomainException('Empty confirm token.');
        }
        $user = $this->users->getByEmailConfirmToken($token);
        $user->confirmSignup();
        $this->users->save($user);
    }
}
