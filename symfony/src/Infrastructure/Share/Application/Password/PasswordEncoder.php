<?php

namespace App\Infrastructure\Share\Application\Password;

/**
 * Class PasswordEncoder.
 */
class PasswordEncoder
{
    public static function encode(string $plainPassword): string
    {
        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);

        return $hash;
    }
}
