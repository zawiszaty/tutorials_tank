<?php

namespace App\Application\Command\Post\Create;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\Exception\CreatePostException;
use App\Domain\Post\Post;
use App\Domain\Post\ValueObject\Content;
use App\Domain\Post\ValueObject\Thumbnail;
use App\Domain\Post\ValueObject\Title;
use App\Infrastructure\Post\Repository\PostRepository;
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
     * @throws CreatePostException
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(CreatePostCommand $command)
    {
        $file = $command->getFile();
        $fileName = Uuid::uuid4() . '-' . Uuid::uuid4() . '.' . $file->guessExtension();
        $file->move(
            'thumbnails',
            $fileName
        );

        $aggregateRoot = Post::create(
          AggregateRootId::fromString(Uuid::uuid4()),
          Title::fromString($command->getTitle()),
            Content::fromString($command->getContent()),
            Thumbnail::fromString('/thumbnails/' . $fileName),
            $command->getType(),
            $command->getUser(),
            $command->getCategory(),
            $command->getShortDescription()
        );

        $this->postRepository->store($aggregateRoot);

        throw new CreatePostException($aggregateRoot->getId());
    }
}
