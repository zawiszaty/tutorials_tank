<?php

namespace App\Application\Command\Comment\Delete;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Command\Post\Delete\DeletePostCommand;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Category\Repository\CategoryRepository;
use App\Infrastructure\Comment\Query\CommentRepositoryElastic;
use App\Infrastructure\Comment\Query\MysqlCommentReadModelRepository;
use App\Infrastructure\Comment\Repository\CommentRepository;
use League\Tactician\CommandBus;

/**
 * Class DeleteCommentHandler
 * @package App\Application\Command\Comment\Delete
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
     * @param CommentRepository $commentRepository
     * @param CommandBus $commandBus
     * @param CommentRepositoryElastic $commentRepositoryElastic
     * @param MysqlCommentReadModelRepository $commentReadModelRepository
     */
    public function __construct(CommentRepository $commentRepository, CommandBus $commandBus, CommentRepositoryElastic $commentRepositoryElastic
        , MysqlCommentReadModelRepository $commentReadModelRepository
    )
    {
        $this->commandBus = $commandBus;
        $this->commentRepository = $commentRepository;
        $this->commentRepositoryElastic = $commentRepositoryElastic;
        $this->commentReadModelRepository = $commentReadModelRepository;
    }

    /**
     * @param DeleteCommentCommand $deleteCommentCommand
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(DeleteCommentCommand $deleteCommentCommand)
    {
        $aggregateRoot = $this->commentRepository->get(AggregateRootId::fromString($deleteCommentCommand->getId()));

        if (!$aggregateRoot->getParrentComment()) {
            $comments = $this->commentRepositoryElastic->search([
                "query" => [
                    "bool" => [
                        "must" => [
                            [
                                "match" => [
                                    "parrentComment" => $aggregateRoot->getId()->toString(),
                                ]
                            ]
                        ],
                    ]
                ]]);

            foreach ($comments['hits']['hits'] as $comment) {
                $comment = $comment['_source'];
                $this->commentReadModelRepository->delete($comment['id']);
            }

        }

        $aggregateRoot->delete($deleteCommentCommand->getUser());
    }
}