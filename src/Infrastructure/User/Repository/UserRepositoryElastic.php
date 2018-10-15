<?php

namespace App\Infrastructure\User\Repository;

use App\Infrastructure\Share\Query\Repository\ElasticRepository;

class UserRepositoryElastic extends ElasticRepository
{
    private const INDEX = 'fos_user';

    /**
     * CategoryRepositoryElastic constructor.
     *
     * @param array $elasticConfig
     */
    public function __construct(array $elasticConfig)
    {
        parent::__construct($elasticConfig, self::INDEX);
    }
}