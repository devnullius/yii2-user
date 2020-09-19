<?php
declare(strict_types=1);

namespace devnullius\user\repositories;

use devnullius\user\entities\User;

final class UserReadRepository
{
    public function find(int $id): ?User
    {
        return User::findOne($id);
    }

    public function findActiveByUsername(string $username): ?User
    {
        return User::findOne(['username' => $username, 'status' => User::STATUS_ACTIVE]);
    }

    public function findActiveByEmail(string $email): ?User
    {
        return User::findOne(['email' => $email, 'status' => User::STATUS_ACTIVE]);
    }

    public function findActiveByPhone(string $phone): ?User
    {
        return User::findOne(['phone' => $phone, 'status' => User::STATUS_ACTIVE]);
    }

    public function findActiveById(int $id): ?User
    {
        return User::findOne(['id' => $id, 'status' => User::STATUS_ACTIVE]);
    }
}
