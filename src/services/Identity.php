<?php
declare(strict_types=1);

namespace devnullius\user\services;

use devnullius\user\entities\User;
use devnullius\user\repositories\UserReadRepository;
use filsh\yii2\oauth2server\Module;
use OAuth2\Storage\UserCredentialsInterface;
use Yii;
use yii\web\IdentityInterface;

final class Identity implements IdentityInterface, UserCredentialsInterface
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $data = self::getOauth()->getServer()->getResourceController()->getToken();

        return !empty($data['user_id']) ? static::findIdentity($data['user_id']) : null;
    }

    private static function getOauth(): Module
    {
        return Yii::$app->getModule('oauth2');
    }

    public static function findIdentity($id)
    {
        $user = self::getRepository()->findActiveById($id);

        return $user ? new self($user) : null;
    }

    private static function getRepository(): UserReadRepository
    {
        return Yii::$container->get(UserReadRepository::class);
    }

    public function getId(): int
    {
        return $this->user->id;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    public function getAuthKey(): string
    {
        return $this->user->auth_key;
    }

    public function checkUserCredentials($username, $password): bool
    {
        if (!$user = self::getRepository()->findActiveByUsername($username)) {
            return false;
        }

        return $user->validatePassword($password);
    }

    public function getUserDetails($username): array
    {
        $user = self::getRepository()->findActiveByUsername($username);

        return ['user_id' => $user->id];
    }

    /**
     * @param $name
     *
     * @return User
     */
    public function __get(string $name)
    {
        if ($name === 'user') {
            return $this->user;
        }

        return $this->user->$name;
    }

    public function __set(string $name, $value)
    {
    }

    public function __isset(string $name)
    {
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
