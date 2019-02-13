<?php

namespace App\Application\Command\Post\Edit;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
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
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function __invoke(EditPostCommand $command): void
    {
        $aggregateRoot = $this->postRepository->get(AggregateRootId::fromString($command->id));

        if (null !== $command->file) {
            $fileName = '/thumbnails/' . FileMover::move($command->file, 'thumbnails');
        } else {
            $fileName = $aggregateRoot->getThumbnail()->toString();
        }

        $aggregateRoot->edit(
            Title::fromString($command->title),
            Content::fromString($command->content),
            Thumbnail::fromString($fileName),
            $command->type,
            $command->user,
            $command->category->getId(),
            $command->shortDescription
        );

        $this->postRepository->store($aggregateRoot);
    }
}
