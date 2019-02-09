<?php

namespace App\Domain\User\Exception;

/**
 * Class UserIsBannedException.
 */
class UserIsBannedException extends \InvalidArgumentException
{
    protected $message = 'Użytkownik jest zbanowany';
}
