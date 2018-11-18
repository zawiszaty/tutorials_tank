<?php

namespace App\Infrastructure\Comment;

use App\Domain\Comment\Event\CommentWasCreated;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Comment\Query\MysqlCommentReadModelRepository;
use App\Infrastructure\Comment\Query\Projections\CommentView;
use App\Infrastructure\Post\Query\Repository\MysqlPostReadModelRepository;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use Broadway\ReadModel\Projector;

class CommentReadProjectionFactory extends Projector
{
    /**
     * @var MysqlCommentReadModelRepository
     */
    private $modelRepository;

    /**
     * @var MysqlPostReadModelRepository
     */
    private $mysqlPostReadModelRepository;

    /**
     * @var MysqlUserReadModelRepository
     */
    private $mysqlUserReadModelRepository;

    public function __construct(
        MysqlCommentReadModelRepository $modelRepository,
        MysqlPostReadModelRepository $mysqlPostReadModelRepository,
        MysqlUserReadModelRepository $mysqlUserReadModelRepository
    ) {
        $this->modelRepository = $modelRepository;
        $this->mysqlPostReadModelRepository = $mysqlPostReadModelRepository;
        $this->mysqlUserReadModelRepository = $mysqlUserReadModelRepository;
    }

    /**
     * @param CommentWasCreated $event
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyCommentWasCreated(CommentWasCreated $event)
    {
        $data = $event->serialize();
        $data['user'] = $this->mysqlUserReadModelRepository->oneByUuid(AggregateRootId::fromString($data['user']));
        $data['post'] = $this->mysqlPostReadModelRepository->oneByUuid(AggregateRootId::fromString($data['post']));

        if ($data['parrentComment']) {
            $data['parrentComment'] = $this->modelRepository->oneByUuid(AggregateRootId::fromString($data['parrentComment']));
        }
        $comment = CommentView::deserialize($data);
        $this->modelRepository->add($comment);
    }
}
