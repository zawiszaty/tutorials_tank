<?php

namespace App\Infrastructure\Post;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\Event\CreatePostEvent;
use App\Domain\Post\Event\PostWasEdited;
use App\Infrastructure\Category\Query\Mysql\MysqlCategoryReadModelRepository;
use App\Infrastructure\Post\Query\Projections\PostView;
use App\Infrastructure\Post\Query\Repository\MysqlPostReadModelRepository;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use Broadway\ReadModel\Projector;

/**
 * Class PostReadProjectionFactory.
 */
class PostReadProjectionFactory extends Projector
{
    /**
     * @var MysqlUserReadModelRepository
     */
    private $mysqlUserReadModelRepository;

    /**
     * @var MysqlCategoryReadModelRepository
     */
    private $categoryReadModelRepository;

    /**
     * @param CreatePostEvent $event
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyCreatePostEvent(CreatePostEvent $event)
    {
        $data = $event->serialize();
        $data['user'] = $this->mysqlUserReadModelRepository->oneByUuid(AggregateRootId::fromString($data['user']));
        $data['category'] = $this->categoryReadModelRepository->oneByUuid(AggregateRootId::fromString($data['category']));
        $data['slug'] = implode('-', explode(' ', $data['title']));
        $data['createdAt'] = new \DateTime();

        $postView = PostView::deserialize($data);
        $this->modelRepository->add($postView);
    }

    /**
     * @param PostWasEdited $event
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyPostWasEdited(PostWasEdited $event)
    {
        $data = $event->serialize();
        $data['slug'] = implode('-', explode(' ', $data['title']));
        $data['user'] = $this->mysqlUserReadModelRepository->oneByUuid(AggregateRootId::fromString($data['user']));
        $data['category'] = $this->categoryReadModelRepository->oneByUuid(AggregateRootId::fromString($data['category']));

        /** @var PostView $postView */
        $postView = $this->modelRepository->oneByUuid(AggregateRootId::fromString($event->getId()));
        $postView->edit($data);
        $this->modelRepository->apply();
    }

    /**
     * @var MysqlPostReadModelRepository
     */
    private $modelRepository;

    /**
     * PostReadProjectionFactory constructor.
     *
     * @param MysqlPostReadModelRepository     $modelRepository
     * @param MysqlUserReadModelRepository     $mysqlUserReadModelRepository
     * @param MysqlCategoryReadModelRepository $categoryReadModelRepository
     */
    public function __construct(
        MysqlPostReadModelRepository $modelRepository,
        MysqlUserReadModelRepository $mysqlUserReadModelRepository,
        MysqlCategoryReadModelRepository $categoryReadModelRepository
    ) {
        $this->modelRepository = $modelRepository;
        $this->mysqlUserReadModelRepository = $mysqlUserReadModelRepository;
        $this->categoryReadModelRepository = $categoryReadModelRepository;
    }
}
