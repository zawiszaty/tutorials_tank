<?php

namespace App\Tests\Application\Utils\User;

use App\Application\Command\User\Create\CreateUserCommand;
use Ramsey\Uuid\Uuid;

/**
 * Class User.
 */
class User
{
    /**
     * @param string $email
     *
     * @throws \Exception
     *
     * @return CreateUserCommand
     */
    public static function create(string $email)
    {
        $command = new CreateUserCommand();
        $command->setAvatar('test'.Uuid::uuid4()->toString());
        $command->setBanned(false);
        $command->setEmail($email);
        $command->setPlainPassword('test');
        $command->setUsername('test'.Uuid::uuid4()->toString());
        $command->setRoles(['ROLE_USER']);
        $command->setSteemit('test');

        return $command;
    }
}
