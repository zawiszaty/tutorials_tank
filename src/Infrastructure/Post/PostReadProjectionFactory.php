<?php

namespace App\Infrastructure\Post;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Post\Event\CreatePostEvent;
use App\Infrastructure\Category\Query\Mysql\MysqlCategoryReadModelRepository;
use App\Infrastructure\Post\Query\Projections\PostView;
use App\Infrastructure\Post\Query\Repository\MysqlPostReadModelRepository;
use App\Infrastructure\PostType\Query\Mysql\MysqlPostTypeReadModelRepository;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use Broadway\ReadModel\Projector;

/**
 * Class PostReadProjectionFactory
 * @package App\Infrastructure\Post
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
     * @throws \Assert\AssertionFailedException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function applyCreatePostEvent(CreatePostEvent $event)
    {
        $data = $event->serialize();
        $data['user'] = $this->mysqlUserReadModelRepository->oneByUuid(AggregateRootId::fromString($data['user']));
        $data['category'] = $this->categoryReadModelRepository->oneByUuid(AggregateRootId::fromString($data['category']));
        $data['slug'] = join('-', explode(' ', $data['title']));
        $data['createdAt'] = new \DateTime();

        $postView = PostView::deserialize($data);
        $this->modelRepository->add($postView);
    }

    /**
     * @var MysqlPostReadModelRepository
     */
    private $modelRepository;

    /**
     * PostReadProjectionFactory constructor.
     * @param MysqlPostReadModelRepository $modelRepository
     * @param MysqlUserReadModelRepository $mysqlUserReadModelRepository
     * @param MysqlCategoryReadModelRepository $categoryReadModelRepository
     */
    public function __construct(
        MysqlPostReadModelRepository $modelRepository,
        MysqlUserReadModelRepository $mysqlUserReadModelRepository,
        MysqlCategoryReadModelRepository $categoryReadModelRepository
    )
    {
        $this->modelRepository = $modelRepository;
        $this->mysqlUserReadModelRepository = $mysqlUserReadModelRepository;
        $this->categoryReadModelRepository = $categoryReadModelRepository;
    }
}