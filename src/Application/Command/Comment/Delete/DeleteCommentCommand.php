<?php

namespace App\Application\Command\Comment\Delete;

/**
 * Class DeleteCommentCommand
 * @package App\Application\Command\Comment\Delete
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