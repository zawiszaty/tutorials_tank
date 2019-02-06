<?php

namespace App\Domain\User\Exception;

/**
 * Class UserIsBannedException
 *
 * @package App\Domain\User\Exception
 */
class UserIsBannedException extends \InvalidArgumentException
{
    protected $message = 'Użytkownik jest zbanowany';
}
