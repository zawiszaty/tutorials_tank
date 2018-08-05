<?php

namespace App\Infrastructure\Category\Repository;

use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Share\Query\Repository\ElasticRepository;
use Broadway\Serializer\Serializable;

class CategoryRepositoryElastic extends ElasticRepository
{
    private const INDEX = 'category';

    public function store(CategoryView $message): void
    {
        $this->add($message->serialize());
    }

    public function __construct(array $elasticConfig)
    {
        parent::__construct($elasticConfig, self::INDEX);
    }
}