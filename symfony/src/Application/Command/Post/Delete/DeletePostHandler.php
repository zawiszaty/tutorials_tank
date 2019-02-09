<?php

namespace App\Application\Command\Post\Delete;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Comment\Query\CommentRepositoryElastic;
use App\Infrastructure\Comment\Query\MysqlCommentReadModelRepository;
use App\Infrastructure\Post\Repository\PostRepository;
use League\Tactician\CommandBus;

/**
 * Class DeletePostHandler.
 */
class DeletePostHandler implements CommandHandlerInterface
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var CommentRepositoryElastic
     */
    private $commentRepositoryElastic;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var MysqlCommentReadModelRepository
     */
    private $commentReadModelRepository;

    /**
     * DeletePostHandler constructor.
     *
     * @param PostRepository                  $postRepository
     * @param CommentRepositoryElastic        $commentRepositoryElastic
     * @param CommandBus                      $commandBus
     * @param MysqlCommentReadModelRepository $commentReadModelRepository
     */
    public function __construct(PostRepository $postRepository, CommentRepositoryElastic $commentRepositoryElastic, CommandBus $commandBus, MysqlCommentReadModelRepository $commentReadModelRepository)
    {
        $this->postRepository = $postRepository;
        $this->commentRepositoryElastic = $commentRepositoryElastic;
        $this->commandBus = $commandBus;
        $this->commentReadModelRepository = $commentReadModelRepository;
    }

    /**
     * @param DeletePostCommand $deletePostCommand
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(DeletePostCommand $deletePostCommand)
    {
//        $comments = $this->commentRepositoryElastic->search([
//            "query" => [
//                "bool" => [
//                    "must" => [
//                        [
//                            "match" => [
//                                "post" => $deletePostCommand->getId(),
//                            ]
//                        ]
//                    ],
//                    "must_not" => [
//                        "exists" => [
//                            "field" => "parrentComment"
//                        ]
//                    ]
//                ]
//            ]]);
//
//        foreach ($comments['hits']['hits'] as $comment) {
//            $comment = $comment['_source'];
//            $command = new DeleteCommentCommand($comment['id'], $comment['user']);
//            $this->commandBus->handle($command);
//        }

        $aggregateRoot = $this->postRepository->get(AggregateRootId::fromString($deletePostCommand->getId()));
        $aggregateRoot->delete($deletePostCommand->getUser());

        $this->postRepository->store($aggregateRoot);
    }
}
