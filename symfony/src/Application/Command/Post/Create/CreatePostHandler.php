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

/**
 * Class CreatePostHandler
 *
 * @package App\Application\Command\Post\Create
 */
class CreatePostHandler implements CommandHandlerInterface
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * CreatePostHandler constructor.
     *
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @param CreatePostCommand $command
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function __invoke(CreatePostCommand $command): void
    {
        $fileName = FileMover::move($command->file, 'thumbnails');
        $aggregateRoot = Post::create(
            AggregateRootId::fromString(Uuid::uuid4()),
            Title::fromString($command->title),
            Content::fromString($command->content),
            Thumbnail::fromString('/thumbnails/' . $fileName),
            $command->type,
            $command->user,
            $command->category->getId(),
            $command->shortDescription
        );

        $this->postRepository->store($aggregateRoot);
    }
}
