<?php

namespace App\Application\Command\Post\Edit;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\Exception\CreatePostException;
use App\Domain\Post\ValueObject\Content;
use App\Domain\Post\ValueObject\Thumbnail;
use App\Domain\Post\ValueObject\Title;
use App\Infrastructure\Post\Repository\PostRepository;
use App\Infrastructure\Share\Application\File\FileMover;

/**
 * Class EditPostHandler.
 */
class EditPostHandler implements CommandHandlerInterface
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * EditPostHandler constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @param EditPostCommand $command
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(EditPostCommand $command): void
    {
        $aggregateRoot = $this->postRepository->get(AggregateRootId::fromString($command->getId()));

        if (null !== $command->getFile()) {
            $fileName = FileMover::move($command->getFile());
        } else {
            $fileName = $aggregateRoot->getThumbnail()->toString();
        }

        $aggregateRoot->edit(
            Title::fromString($command->getTitle()),
            Content::fromString($command->getContent()),
            Thumbnail::fromString($fileName),
            $command->getType(),
            $command->getUser(),
            $command->getCategory(),
            $command->getShortDescription()
        );

        $this->postRepository->store($aggregateRoot);
    }
}
