<?php

namespace App\Domain\User\Assert;

use App\Domain\User\Exception\UserIsAdminException;
use App\Domain\User\Exception\UserNotIsAdminException;
use App\Domain\User\ValueObject\Roles;

/**
 * Class UserIsNotAdmin.
 */
class UserIsAdmin
{
    /**
     * @throws UserIsAdminException
     * @throws UserNotIsAdminException
     */
    public static function check(Roles $roles)
    {
        if (!\in_array('ROLE_ADMIN', $roles->toArray())) {
            throw new UserNotIsAdminException();
        }
    }
}
