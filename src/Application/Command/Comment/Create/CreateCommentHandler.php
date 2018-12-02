<?php

namespace App\Application\Command\Comment\Create;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Comment\Comment;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\ValueObject\Content;
use App\Infrastructure\Comment\Repository\CommentRepository;
use App\Infrastructure\Notification\Strategy\NotificationAbstractFactory;
use Ramsey\Uuid\Uuid;

class CreateCommentHandler implements CommandHandlerInterface
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * CreateCommentHandler constructor.
     *
     * @param CommentRepository $commentRepository
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param CreateCommentCommand $command
     *
     * @throws \Assert\AssertionFailedException
     * @throws \ZMQSocketException
     */
    public function __invoke(CreateCommentCommand $command)
    {
        $aggregateRoot = Comment::create(
            AggregateRootId::fromString(Uuid::uuid4()),
            Content::fromString($command->getContent()),
            $command->getParentComment(),
            $command->getPost(),
            $command->getUser()
        );
        $this->commentRepository->store($aggregateRoot);
    }
}
