<?php

namespace App\Application\Command\User\ConfirmUser;

/**
 * Class ConfirmUserCommand.
 */
class ConfirmUserCommand
{
    /**
     * @var string
     */
    private $token;

    /**
     * ConfirmUserCommand constructor.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
