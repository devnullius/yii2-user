<?php
declare(strict_types=1);

namespace devnullius\user\useCases\auth;

use devnullius\user\entities\User;
use devnullius\user\forms\auth\LoginForm;
use devnullius\user\repositories\UserRepository;
use DomainException;

final class AuthService
{
    private UserRepository $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth(LoginForm $form): User
    {
        $user = $this->users->findByUsernameOrEmail($form->username);
        if (!$user || !$user->isActive() || !$user->validatePassword($form->password)) {
            throw new DomainException('Undefined user or password.');
        }

        return $user;
    }
}
