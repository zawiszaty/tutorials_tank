<?php

namespace App\Domain\User\Exception;

class UserIsBannedException extends \InvalidArgumentException
{
    protected $message = 'Użytkownik jest zbanowany';
}
