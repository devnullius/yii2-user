<?php
declare(strict_types=1);

namespace devnullius\user\useCases;

use devnullius\queue\addon\wrappers\transaction\TransactionWrapper;
use devnullius\user\entities\User;
use devnullius\user\forms\UserCreateForm;
use devnullius\user\forms\UserEditForm;
use devnullius\user\repositories\UserRepository;
use devnullius\user\services\RoleManager;
use Exception;
use Throwable;
use Yii;
use yii\db\StaleObjectException;

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
        try {
            $this->transaction->wrap(function () use ($user, $form) {
                $this->repository->save($user);
                $this->roles->assign($user->id, $form->role);
            });
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }

        return $user;
    }

    public function edit(int $id, UserEditForm $form): void
    {
        $user = $this->repository->get($id);
        $user->edit(
            $form->username,
            $form->email,
            $form->phone
        );
        try {
            $this->transaction->wrap(function () use ($user, $form) {

                if ((bool)$form->generateTokens === true) {
                    $user->token = Yii::$app->security->generateRandomString();
                    $user->secret = Yii::$app->security->generateRandomString();
                }
                $this->repository->save($user);
                $this->roles->assign($user->id, $form->role);
            });
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
    }

    public function assignRole(int $id, string $role): void
    {
        $user = $this->repository->get($id);
        try {
            $this->roles->assign($user->id, $role);
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
    }

    public function remove(int $id): void
    {
        $user = $this->repository->get($id);
        try {
            $this->repository->remove($user);
        } catch (StaleObjectException $e) {
            Yii::$app->errorHandler->logException($e);
        } catch (Throwable $e) {
            Yii::$app->errorHandler->logException($e);
        }
    }
}
