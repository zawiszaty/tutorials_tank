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
     * @param string $email
     * @param string $token
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $email, string $token)
    {
        Assertion::email($email);
        $this->email = $email;
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
