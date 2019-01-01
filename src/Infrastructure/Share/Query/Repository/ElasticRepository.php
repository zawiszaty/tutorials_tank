<?php

declare(strict_types=1);

namespace App\Infrastructure\Share\Query\Repository;

use App\Application\Query\Collection;
use App\Domain\Common\Event\AbstractEvent;
use Assert\Assertion;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

/**
 * Class ElasticRepository.
 */
abstract class ElasticRepository
{
    /**
     * @param AbstractEvent $message
     */
    public function store(AbstractEvent $message): void
    {
        $this->add($message->serialize());
    }

    /**
     * @param array $query
     *
     * @return array
     */
    public function search(array $query): array
    {
        $finalQuery = [];

        $finalQuery['index'] = $finalQuery['type'] = $this->index; // To be deleted in elastic 7
        $finalQuery['body'] = $query;

        return $this->client->search($finalQuery);
    }

    public function refresh(): void
    {
        if ($this->client->indices()->exists(['index' => $this->index])) {
            $this->client->indices()->refresh(['index' => $this->index]);
        }
    }

    public function delete(): void
    {
        if ($this->client->indices()->exists(['index' => $this->index])) {
            $this->client->indices()->delete(['index' => $this->index]);
        }
    }

    public function boot(): void
    {
        if (!$this->client->indices()->exists(['index' => $this->index])) {
            $this->client->indices()->create(['index' => $this->index]);
        }
    }

    /**
     * @param array $document
     *
     * @return array
     */
    protected function add(array $document): array
    {
        $query['index'] = $query['type'] = $this->index;
        $query['id'] = $document['id'] ?? null;
        $query['body'] = $document;

        return $this->client->index($query);
    }

    /**
     * @param int    $page
     * @param int    $limit
     * @param string $queryString
     *
     * @return Collection
     */
    public function page(int $page = 1, int $limit = 50, array $queryString = []): Collection
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

    /**
     * @param string $id
     */
    public function deleteRow(string $id): void
    {
        $query['index'] = $query['type'] = $this->index;
        $query['id'] = $id;
        $this->client->delete($query);
    }

    /**
     * ElasticRepository constructor.
     *
     * @param array  $config
     * @param string $index
     */
    public function __construct(array $config, string $index)
    {
        $this->client = ClientBuilder::fromConfig($config, true);
        $this->index = $index;
    }

    /**
     * @param string $id
     *
     * @return array
     */
    public function get(string $id)
    {
        $params = [
            'index' => $this->index,
            'type' => $this->index,
            'id' => $id,
        ];

        return $this->client->get($params);
    }

    /** @var string */
    protected $index;

    /** @var Client */
    protected $client;
}
