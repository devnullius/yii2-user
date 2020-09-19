<?php
declare(strict_types=1);

namespace devnullius\user\useCases\auth;

use devnullius\user\entities\User;
use devnullius\user\repositories\UserRepository;
use DomainException;
use Yii;
use yii\base\Exception;

final class NetworkService
{
    private UserRepository $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth(string $network, string $identity): User
    {
        if ($user = $this->users->findByNetworkIdentity($network, $identity)) {
            return $user;
        }
        try {
            $user = User::signupByNetwork($network, $identity);
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
        $this->users->save($user);

        return $user;
    }

    public function attach(int $userId, string $network, string $identity): void
    {
        if ($this->users->findByNetworkIdentity($network, $identity)) {
            throw new DomainException('Network already signed up.');
        }
        $user = $this->users->get($userId);
        $user->attachNetwork($network, $identity);
        $this->users->save($user);
    }
}
