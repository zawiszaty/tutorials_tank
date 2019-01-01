<?php

namespace App\Domain\User\Exception;

use Symfony\Component\HttpFoundation\Response;

class UserIsAdminException extends \Exception
{
    protected $message = 'User Is Admin';

    protected $code = Response::HTTP_BAD_REQUEST;
}
