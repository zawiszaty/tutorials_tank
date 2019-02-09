<?php

namespace App\Domain\User\Assert;

use App\Domain\User\Exception\UserIsBannedException;
use App\Domain\User\User;

/**
 * Class UserIsBanned.
 */
class UserIsBanned
{
    /**
     * @param User $user
     */
    public static function check(User $user)
    {
        if ($user->isBanned()) {
            throw new UserIsBannedException();
        }
    }
}
