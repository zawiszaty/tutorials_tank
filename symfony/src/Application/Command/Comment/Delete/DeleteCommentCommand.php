<?php

namespace App\Application\Command\Comment\Delete;

/**
 * Class DeleteCommentCommand.
 */
class DeleteCommentCommand
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
     * DeleteCommentCommand constructor.
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
