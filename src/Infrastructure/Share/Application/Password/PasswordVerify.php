<?php

namespace App\Infrastructure\Share\Application\Password;

use App\Domain\User\Exception\PasswordIsBadException;

/**
 * Class PasswordVerify.
 */
class PasswordVerify
{
    /**
     * @param string $oldPassword
     * @param string $currentPassword
     */
    public static function verify(string $oldPassword, string $currentPassword): void
    {
        if (
        !password_verify(
            $oldPassword,
            $currentPassword
        )
        ) {
            throw new PasswordIsBadException();
        }
    }
}
