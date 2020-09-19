<?php
declare(strict_types=1);

namespace devnullius\user\entities\events;

use devnullius\queue\addon\events\QueueEvent;
use devnullius\user\entities\User;

class UserSignUpRequested implements QueueEvent
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
