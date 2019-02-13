<?php

namespace App\Application\Command\User\SendEmail;

use Assert\Assertion;

/**
 * Class SendEmailCommand.
 */
class SendEmailCommand
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $token;

    /**
     * SendEmailCommand constructor.
     *
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $email, string $token)
    {
        Assertion::email($email);
        $this->email = $email;
        $this->token = $token;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
