<?php

namespace App\Domain\User\Assert;

use App\Domain\User\Exception\UserIsBannedException;
use App\Domain\User\Exception\UserIsEnabledException;
use App\Domain\User\User;

/**
 * Class UserIsEnabled
 * @package App\Domain\User\Assert
 */
class UserIsEnabled
{
    /**
     * @param User $user
     */
    public static function check(User $user)
    {
        if ($user->isEnabled()) {
            throw new UserIsEnabledException();
        }
    }
}