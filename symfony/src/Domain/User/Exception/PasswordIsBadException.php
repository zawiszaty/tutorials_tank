<?php

namespace App\Domain\User\Exception;

/**
 * Class PasswordIsBadException.
 */
class PasswordIsBadException extends \InvalidArgumentException
{
    protected $message = 'Hasło jest nie prawidłowe';
}
