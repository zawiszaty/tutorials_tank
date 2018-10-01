<?php

namespace App\Domain\User\Exception;

class UserIsEnabledException extends \InvalidArgumentException
{
    protected $message = 'Użytkownik jest potwierdzony';
}
