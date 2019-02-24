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
    private $user;

    /**
     * @var string
     */
    private $type;

    /**
     * SendEmailCommand constructor.
     *
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $email, string $user, string $type)
    {
        Assertion::email($email);
        $this->email = $email;
        $this->user = $user;
        $this->type = $type;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
