<?php

namespace App\Application\Command\User\UnBannedUser;

/**
 * Class BannedUserCommand.
 */
class UnBannedUserCommand
{
    /**
     * @var string
     */
    private $id;

    /**
     * BannedUserCommand constructor.
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
