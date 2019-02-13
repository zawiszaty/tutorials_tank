<?php

namespace App\Application\Command\Comment\Delete;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Comment\Query\CommentRepositoryElastic;
use App\Infrastructure\Comment\Query\MysqlCommentReadModelRepository;
use App\Infrastructure\Comment\Repository\CommentRepository;
use League\Tactician\CommandBus;

/**
 * Class DeleteCommentHandler.
 */
class DeleteCommentHandler implements CommandHandlerInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var CommentRepositoryElastic
     */
    private $commentRepositoryElastic;

    /**
     * @var MysqlCommentReadModelRepository
     */
    private $commentReadModelRepository;

    /**
     * DeleteCommentHandler constructor.
     */
    public function __construct(
        CommentRepository $commentRepository,
        CommandBus $commandBus,
        CommentRepositoryElastic $commentRepositoryElastic,
        MysqlCommentReadModelRepository $commentReadModelRepository
    ) {
        $this->commandBus = $commandBus;
        $this->commentRepository = $commentRepository;
        $this->commentRepositoryElastic = $commentRepositoryElastic;
        $this->commentReadModelRepository = $commentReadModelRepository;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(DeleteCommentCommand $deleteCommentCommand)
    {
        $aggregateRoot = $this->commentRepository->get(AggregateRootId::fromString($deleteCommentCommand->getId()));
        $aggregateRoot->delete($deleteCommentCommand->getUser());
        $this->commentRepository->store($aggregateRoot);
    }
}
