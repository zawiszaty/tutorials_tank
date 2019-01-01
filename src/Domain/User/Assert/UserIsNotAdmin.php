<?php

namespace App\Domain\User\Assert;

use App\Domain\User\Exception\UserIsAdminException;
use App\Domain\User\ValueObject\Roles;

class UserIsNotAdmin
{
    public static function check(Roles $roles)
    {
        if (in_array('ROLE_ADMIN', $roles->toArray())) {
            throw new UserIsAdminException();
        }
    }
}
