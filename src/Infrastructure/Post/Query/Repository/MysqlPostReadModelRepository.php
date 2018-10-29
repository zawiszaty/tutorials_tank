<?php

namespace App\Infrastructure\Post\Query\Repository;

use App\Infrastructure\Post\Query\Projections\PostView;
use App\Infrastructure\Share\Query\Repository\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;

class MysqlPostReadModelRepository extends MysqlRepository
{
    /**
     * @param PostView $postView
     */
    public function add(PostView $postView): void
    {
        $this->register($postView);
    }

    /**
     * MysqlUserReadModelRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->class = PostView::class;
        parent::__construct($entityManager);
    }
}