<?php

namespace App\Application\Command\User\ChangeEmail;

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

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param null|string $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}
