<?php

namespace App\Infrastructure\User\Repository;

use App\Application\Query\Collection;
use App\Infrastructure\Share\Query\Repository\ElasticRepository;
use Assert\Assertion;

/**
 * Class UserRepositoryElastic.
 */
class UserRepositoryElastic extends ElasticRepository
{
    private const INDEX = 'fos_user';

    /**
     * CategoryRepositoryElastic constructor.
     */
    public function __construct(array $elasticConfig)
    {
        parent::__construct($elasticConfig, self::INDEX);
    }

    public function getByDynamicBody(int $page = 1, int $limit = 50, array $queryString = [], string $sort = 'desc'): Collection
    {
        Assertion::greaterThan($page, 0, 'Pagination need to be > 0');

        $query = [];

        $query['index'] = $query['type'] = $this->index;
        $query['from'] = ($page - 1) * $limit;
        $query['size'] = $limit;
        $query['body'] = $queryString;

        $response = $this->client->search($query);

        $collection = new Collection($page, $limit, $response['hits']['total'], array_map(function (array $item) {
            return $item['_source'];
        }, $response['hits']['hits']));

        return $collection;
    }
}
