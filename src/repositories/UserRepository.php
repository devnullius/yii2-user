<?php
declare(strict_types=1);

namespace devnullius\user\repositories;

use devnullius\helper\exceptions\NotFoundException;
use devnullius\queue\addon\dispatchers\EventDispatcher;
use devnullius\user\entities\User;
use RuntimeException;
use Throwable;
use yii\db\StaleObjectException;
use function assert;

final class UserRepository
{
    private EventDispatcher $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function findByUsernameOrEmail(string $value): ?User
    {
        return User::find()->andWhere(['or', ['username' => $value], ['email' => $value]])->one();
    }

    public function findByNetworkIdentity(string $network, string $identity): ?User
    {
        return User::find()->joinWith('networks n')->andWhere(['n.network' => $network, 'n.identity' => $identity])->one();
    }

    public function get(int $id): User
    {
        return $this->getBy(['id' => $id]);
    }

    private function getBy(array $condition): User
    {
        $user = User::find()->andWhere($condition)->limit(1)->one();
        if ($user === null) {
            throw new NotFoundException('User not found.');
        }
        assert($user instanceof User);

        return $user;
    }

    public function getByEmailConfirmToken(string $token): User
    {
        return $this->getBy(['email_confirm_token' => $token]);
    }

    public function getByEmail(string $email): User
    {
        return $this->getBy(['email' => $email]);
    }

    public function getByPasswordResetToken(string $token): User
    {
        return $this->getBy(['password_reset_token' => $token]);
    }

    public function existsByPasswordResetToken(string $token): bool
    {
        return (bool)User::findByPasswordResetToken($token);
    }

    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new RuntimeException('Saving error.');
        }
        $this->dispatcher->dispatchAll($user->releaseEvents());
    }

    /**
     * @param User $user
     *
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function remove(User $user): void
    {
        if (!$user->delete()) {
            throw new RuntimeException('Removing error.');
        }
        $this->dispatcher->dispatchAll($user->releaseEvents());
    }
}
