<?php
declare(strict_types=1);

namespace devnullius\user\services;

use DomainException;
use Exception;
use yii\rbac\ManagerInterface;

class RoleManager
{
    private ManagerInterface $manager;

    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param $userId
     * @param $name
     *
     * @throws Exception
     */
    public function assign($userId, $name): void
    {
        if (!$role = $this->manager->getRole($name)) {
            throw new DomainException('Role "' . $name . '" does not exist.');
        }
        $this->manager->revokeAll($userId);
        $this->manager->assign($role, $userId);
    }
}
