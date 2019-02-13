<?php

namespace App\Application\Command\User\ChangeEmail;

/**
 * Class ChangeEmailCommand.
 */
class ChangeEmailCommand
{
    /**
     * @var string|null
     */
    public $id;

    /**
     * @var string|null
     */
    public $email;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}
