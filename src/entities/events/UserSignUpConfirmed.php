<?php
declare(strict_types=1);

namespace devnullius\user\entities\events;

use devnullius\queue\addon\events\QueueEvent;

class UserSignUpConfirmed implements QueueEvent
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
