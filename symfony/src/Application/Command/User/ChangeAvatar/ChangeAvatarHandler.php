<?php

namespace App\Application\Command\User\ChangeAvatar;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\Exception\AvatarWasChanged;
use App\Infrastructure\Share\Application\File\FileMover;

/**
 * Class ChangeAvatarHandler.
 */
class ChangeAvatarHandler implements CommandHandlerInterface
{
    /**
     * @var \App\Infrastructure\User\Repository\UserRepository
     */
    private $aggregatRepository;

    /**
     * ConfirmUserHandler constructor.
     *
     * @param \App\Infrastructure\User\Repository\UserRepository $aggregatRepository
     */
    public function __construct(\App\Infrastructure\User\Repository\UserRepository $aggregatRepository)
    {
        $this->aggregatRepository = $aggregatRepository;
    }

    /**
     * @param ChangeAvatarCommand $command
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function __invoke(ChangeAvatarCommand $command): void
    {
        $user = $this->aggregatRepository->get(AggregateRootId::fromString($command->getId()));
        $fileName = FileMover::move($command->getFile(), 'avatars');
        $user->changeAvatar('/avatars/'.$fileName);
        $this->aggregatRepository->store($user);

        throw new AvatarWasChanged('/avatars/'.$fileName);
    }
}
