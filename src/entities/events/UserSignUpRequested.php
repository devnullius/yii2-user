<?php
declare(strict_types=1);

namespace devnullius\user\entities\events;

use devnullius\queue\addon\events\QueueEvent;
use devnullius\user\entities\UserEntity;

final class UserSignUpRequested implements QueueEvent
{
    public UserEntity $user;

    public function __construct(UserEntity $user)
    {
        $this->user = $user;
    }
}
