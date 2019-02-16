<?php

namespace App\Domain\User\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserIsAdminException.
 */
class UserNotIsAdminException extends \Exception
{
    protected $message = 'User Is Not Admin';

    protected $code = Response::HTTP_BAD_REQUEST;
}
