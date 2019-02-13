<?php

namespace App\Application\Query\Post\GetAll;

use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Post\Query\Repository\PostRepositoryElastic;

/**
 * Class GetAllHandler.
 */
class GetAllHandler implements QueryHandlerInterface
{
    /**
     * @var PostRepositoryElastic
     */
    private $repositoryElastic;

    /**
     * GetAllHandler constructor.
     */
    public function __construct(PostRepositoryElastic $repositoryElastic)
    {
        $this->repositoryElastic = $repositoryElastic;
    }

    /**
     * @return \App\Application\Query\Collection
     */
    public function __invoke(GetAllCommand $command)
    {
        if ($command->getQuery()) {
            $query = [
                'query' => [
                    'wildcard' => [
                        'title' => '*' . $command->getQuery() . '*',
                    ],
                ],
            ];
        } else {
            $query = [];
        }
        $data = $this->repositoryElastic->pageByCreatedAt($command->getPage(), $command->getLimit(), $query);

        return $data;
    }
}
