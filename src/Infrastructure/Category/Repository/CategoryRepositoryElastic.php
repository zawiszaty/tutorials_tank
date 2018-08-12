<?php

namespace App\Infrastructure\Category\Repository;

use App\Infrastructure\Share\Query\Repository\ElasticRepository;

/**
 * Class CategoryRepositoryElastic
 * @package App\Infrastructure\Category\Repository
 */
class CategoryRepositoryElastic extends ElasticRepository
{
    /**
     *
     */
    private const INDEX = 'category';

    /**
     * CategoryRepositoryElastic constructor.
     * @param array $elasticConfig
     */
    public function __construct(array $elasticConfig)
    {
        parent::__construct($elasticConfig, self::INDEX);
    }
}