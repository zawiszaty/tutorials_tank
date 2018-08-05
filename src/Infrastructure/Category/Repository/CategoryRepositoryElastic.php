<?php

namespace App\Infrastructure\Category\Repository;

use App\Domain\Category\Event\CategoryWasCreated;
use App\Infrastructure\Share\Query\Repository\ElasticRepository;

class CategoryRepositoryElastic extends ElasticRepository
{
    private const INDEX = 'category';

    public function store(CategoryWasCreated $message): void
    {
        $this->add($message->serialize());
    }

    public function __construct(array $elasticConfig)
    {
        parent::__construct($elasticConfig, self::INDEX);
    }
}