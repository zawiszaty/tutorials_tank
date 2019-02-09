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
     *
     * @param string $id
     * @param string $user
     */
    public function __construct(string $id, string $user)
    {
        $this->id = $id;
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }
}
