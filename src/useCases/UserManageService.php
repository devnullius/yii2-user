<?php
declare(strict_types=1);

namespace devnullius\user\useCases;

use devnullius\queue\addon\wrappers\transaction\TransactionWrapper;
use devnullius\user\entities\User;
use devnullius\user\forms\UserCreateForm;
use devnullius\user\forms\UserEditForm;
use devnullius\user\repositories\UserRepository;
use devnullius\user\services\RoleManager;
use Yii;

final class UserManageService
{
    private UserRepository $repository;
    private RoleManager $roles;
    private TransactionWrapper $transaction;

    public function __construct(
        UserRepository $repository,
        RoleManager $roles,
        TransactionWrapper $transaction
    ) {
        $this->repository = $repository;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }

    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->phone,
            $form->password
        );
        $this->transaction->wrap(function () use ($user, $form) {
            $this->repository->save($user);
            $this->roles->assign($user->id, $form->role);
        });

        return $user;
    }

    public function edit($id, UserEditForm $form): void
    {
        $user = $this->repository->get($id);
        $user->edit(
            $form->username,
            $form->email,
            $form->phone
        );
        $this->transaction->wrap(function () use ($user, $form) {

            if ((bool)$form->generateTokens === true) {
                $user->token = Yii::$app->security->generateRandomString();
                $user->secret = Yii::$app->security->generateRandomString();
            }
            $this->repository->save($user);
            $this->roles->assign($user->id, $form->role);
        });
    }

    public function assignRole($id, $role): void
    {
        $user = $this->repository->get($id);
        $this->roles->assign($user->id, $role);
    }

    public function remove(int $id): void
    {
        $user = $this->repository->get($id);
        $this->repository->remove($user);
    }
}
