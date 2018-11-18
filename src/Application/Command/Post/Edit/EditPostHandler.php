<?php

namespace App\Application\Command\Post\Edit;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\Exception\CreatePostException;
use App\Domain\Post\Post;
use App\Domain\Post\ValueObject\Content;
use App\Domain\Post\ValueObject\Thumbnail;
use App\Domain\Post\ValueObject\Title;
use App\Domain\PostType\ValueObject\Type;
use App\Infrastructure\Post\Repository\PostRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * Class EditPostHandler
 * @package App\Application\Command\Post\Edit
 */
class EditPostHandler implements CommandHandlerInterface
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
     * @param EditPostCommand $command
     * @throws CreatePostException
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function __invoke(EditPostCommand $command)
    {
        $aggregateRoot = $this->postRepository->get(AggregateRootId::fromString($command->getId()));

        if ($aggregateRoot->getUser() !== $command->getUser()) {
            throw new AccessDeniedException();
        }

        if ($command->getFile() !== null) {
            $file = $command->getFile();
            $fileName = '/thumbnails/' . Uuid::uuid4() . '-' . Uuid::uuid4() . '.' . $file->guessExtension();
            $file->move(
                'thumbnails',
                $fileName
            );
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

        throw new CreatePostException($aggregateRoot->getId());
    }
}