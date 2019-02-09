<?php

namespace App\Domain\User\Exception;

/**
 * Class UserIsEnabledException.
 */
class UserIsEnabledException extends \InvalidArgumentException
{
    protected $message = 'Użytkownik jest potwierdzony';
}
