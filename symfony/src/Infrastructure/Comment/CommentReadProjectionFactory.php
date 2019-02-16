<?php

namespace App\Infrastructure\Comment;

use App\Domain\Comment\Event\CommentWasCreated;
use App\Domain\Comment\Event\CommentWasDeletedEvent;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Comment\Query\CommentRepositoryElastic;
use App\Infrastructure\Comment\Query\MysqlCommentReadModelRepository;
use App\Infrastructure\Comment\Query\Projections\CommentView;
use App\Infrastructure\Notification\Strategy\NotificationAbstractFactory;
use App\Infrastructure\Post\Query\Repository\MysqlPostReadModelRepository;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use Broadway\ReadModel\Projector;

/**
 * Class CommentReadProjectionFactory.
 */
final class CommentReadProjectionFactory extends Projector
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

    /**
     * @var NotificationAbstractFactory
     */
    private $notificationAbstractFactory;

    /**
     * @var CommentRepositoryElastic
     */
    private $commentRepositoryElastic;

    /**
     * CommentReadProjectionFactory constructor.
     */
    public function __construct(
        MysqlCommentReadModelRepository $modelRepository,
        MysqlPostReadModelRepository $mysqlPostReadModelRepository,
        MysqlUserReadModelRepository $mysqlUserReadModelRepository,
        NotificationAbstractFactory $notificationAbstractFactory,
        CommentRepositoryElastic $commentRepositoryElastic
    ) {
        $this->modelRepository = $modelRepository;
        $this->mysqlPostReadModelRepository = $mysqlPostReadModelRepository;
        $this->mysqlUserReadModelRepository = $mysqlUserReadModelRepository;
        $this->notificationAbstractFactory = $notificationAbstractFactory;
        $this->commentRepositoryElastic = $commentRepositoryElastic;
    }

    /**
     * @throws \Assert\AssertionFailedException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyCommentWasCreated(CommentWasCreated $event): void
    {
        $data = $event->serialize();
        $data['user'] = $this->mysqlUserReadModelRepository->oneByUuid(AggregateRootId::fromString($data['user']));
        $data['post'] = $this->mysqlPostReadModelRepository->oneByUuid(AggregateRootId::fromString($data['post']));

        if ($data['parrentComment']) {
            $data['parrentComment'] = $this->modelRepository->oneByUuid(AggregateRootId::fromString($data['parrentComment']));
        }

        $comment = CommentView::deserialize($data);
        $this->modelRepository->add($comment);
        $this->commentRepositoryElastic->store($comment->serialize());

        $this->notificationAbstractFactory->create('comment', [
            'user'    => $comment->getFullPost()->getUser(),
            'content' => [
                'post' => [
                    'id'    => $comment->getFullPost()->getId(),
                    'title' => $comment->getFullPost()->getTitle(),
                ],
                'sender' => [
                    'id'       => $comment->getFullUser()->getId(),
                    'username' => $comment->getFullUser()->getUsername(),
                    'avatar'   => $comment->getFullUser()->getAvatar(),
                ],
            ],
            'type' => 'comment',
        ]);
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function applyCommentWasDeletedEvent(CommentWasDeletedEvent $commentWasDeletedEvent): void
    {
        $this->modelRepository->delete($commentWasDeletedEvent->getId());
        $this->commentRepositoryElastic->deleteRow($commentWasDeletedEvent->getId());
    }
}
