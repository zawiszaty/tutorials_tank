<?php

namespace App\Application\Command\Comment\Create;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Comment\Comment;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\ValueObject\Content;
use App\Infrastructure\Comment\Repository\CommentRepository;
use Ramsey\Uuid\Uuid;

/**
 * Class CreateCommentHandler.
 */
class CreateCommentHandler implements CommandHandlerInterface
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * CreateCommentHandler constructor.
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function __invoke(CreateCommentCommand $command)
    {
        $aggregateRoot = Comment::create(
            AggregateRootId::fromString(Uuid::uuid4()),
            Content::fromString($command->content),
            $command->parentComment,
            $command->post->getId(),
            $command->user
        );
        $this->commentRepository->store($aggregateRoot);
    }
}
