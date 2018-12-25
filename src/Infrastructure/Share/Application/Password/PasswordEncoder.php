<?php

namespace App\Infrastructure\Share\Application\Password;

/**
 * Class PasswordEncoder
 * @package App\Infrastructure\Share\Application\Password
 */
class PasswordEncoder
{
    /**
     * @param string $plainPassword
     * @return string
     */
    public static function encode(string $plainPassword): string
    {
        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);

        return $hash;
    }
}