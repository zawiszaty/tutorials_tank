<?php

namespace App\Application\Command\User\BannedUser;

/**
 * Class BannedUserCommand.
 */
class BannedUserCommand
{
    /**
     * @var string
     */
    private $id;

    /**
     * BannedUserCommand constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
