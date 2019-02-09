<?php

namespace App\Domain\User\Exception;

/**
 * Class PasswordIsBadException
 *
 * @package App\Domain\User\Exception
 */
class PasswordIsBadException extends \InvalidArgumentException
{
    protected $message = 'Hasło jest nie prawidłowe';
}
