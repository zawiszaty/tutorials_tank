<?php

namespace App\Application\Command\Post\Create;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\Post;
use App\Domain\Post\ValueObject\Content;
use App\Domain\Post\ValueObject\Thumbnail;
use App\Domain\Post\ValueObject\Title;
use App\Infrastructure\Post\Repository\PostRepository;
use App\Infrastructure\Share\Application\File\FileMover;
use Ramsey\Uuid\Uuid;

class CreatePostHandler implements CommandHandlerInterface
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @param CreatePostCommand $command
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(CreatePostCommand $command): void
    {
        $fileName = FileMover::move($command->getFile());
        $aggregateRoot = Post::create(
            AggregateRootId::fromString(Uuid::uuid4()),
            Title::fromString($command->getTitle()),
            Content::fromString($command->getContent()),
            Thumbnail::fromString('/thumbnails/'.$fileName),
            $command->getType(),
            $command->getUser(),
            $command->getCategory(),
            $command->getShortDescription()
        );

        $this->postRepository->store($aggregateRoot);
    }
}
