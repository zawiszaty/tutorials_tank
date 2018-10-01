<?php

namespace App\Application\Command\User\ConfirmUser;

/**
 * Class ConfirmUserCommand
 * @package App\Application\Command\User\ConfirmUser
 */
class ConfirmUserCommand
{
    /**
     * @var string
     */
    private $token;

    /**
     * ConfirmUserCommand constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}