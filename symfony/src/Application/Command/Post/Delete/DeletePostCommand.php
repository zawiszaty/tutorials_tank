<?php

namespace App\Application\Command\Post\Delete;

/**
 * Class DeletePostCommand.
 */
class DeletePostCommand
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $user;

    /**
     * DeletePostCommand constructor.
     */
    public function __construct(string $id, string $user)
    {
        $this->id = $id;
        $this->user = $user;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUser(): string
    {
        return $this->user;
    }
}
