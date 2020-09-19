<?php
declare(strict_types=1);

namespace devnullius\user\services;

use DomainException;
use Exception;
use yii\rbac\ManagerInterface;

final class RoleManager
{
    private ManagerInterface $manager;

    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param int    $userId
     * @param string $name
     *
     * @throws Exception
     */
    public function assign(int $userId, string $name): void
    {
        if (!$role = $this->manager->getRole($name)) {
            throw new DomainException('Role "' . $name . '" does not exist.');
        }
        $this->manager->revokeAll($userId);
        $this->manager->assign($role, $userId);
    }
}
