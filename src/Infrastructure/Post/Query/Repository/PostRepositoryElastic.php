<?php

namespace App\Infrastructure\Post\Query\Repository;

use App\Application\Query\Collection;
use App\Infrastructure\Share\Query\Repository\ElasticRepository;
use Assert\Assertion;

class PostRepositoryElastic extends ElasticRepository
{
    private const INDEX = 'post';

    /**
     * CategoryRepositoryElastic constructor.
     *
     * @param array $elasticConfig
     */
    public function __construct(array $elasticConfig)
    {
        parent::__construct($elasticConfig, self::INDEX);
    }

    /**
     * @param int    $page
     * @param int    $limit
     * @param string $queryString
     *
     * @return Collection
     */
    public function pageByCreatedAt(int $page = 1, int $limit = 50, ?array $queryString = []): Collection
    {
        Assertion::greaterThan($page, 0, 'Pagination need to be > 0');

        $query = [];

        $query['index'] = $query['type'] = $this->index;
        $query['from'] = ($page - 1) * $limit;
        $query['size'] = $limit;
        $query['body'] = $queryString;
        $query['sort'] = ['createdAt:desc'];

        $response = $this->client->search($query);

        $collection = new Collection($page, $limit, $response['hits']['total'], array_map(function (array $item) {
            return $item['_source'];
        }, $response['hits']['hits']));

        return $collection;
    }
}
