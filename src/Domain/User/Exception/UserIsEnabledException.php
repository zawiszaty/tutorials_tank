<?php

namespace App\Domain\User\Exception;

/**
 * Class UserIsEnabledException
 *
 * @package App\Domain\User\Exception
 */
class UserIsEnabledException extends \InvalidArgumentException
{
    protected $message = 'Użytkownik jest potwierdzony';
}
