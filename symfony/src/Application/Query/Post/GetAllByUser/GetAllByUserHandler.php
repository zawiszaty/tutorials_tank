<?php

namespace App\Application\Query\Post\GetAllByUser;

use App\Application\Query\Collection;
use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Post\Query\Repository\PostRepositoryElastic;

/**
 * Class GetOneBySlugHandler.
 */
class GetAllByUserHandler implements QueryHandlerInterface
{
    /**
     * @var PostRepositoryElastic
     */
    private $repositoryElastic;

    /**
     * GetOneBySlugHandler constructor.
     */
    public function __construct(PostRepositoryElastic $repositoryElastic)
    {
        $this->repositoryElastic = $repositoryElastic;
    }

    /**
     * @param GetOneBySlugHandler $command
     *
     * @return array
     */
    public function __invoke(GetAllByUserCommand $command): Collection
    {
        $query = [
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'match' => [
                                'user' => $command->getUser(),
                            ],
                        ],
                        [
                            'wildcard' => [
                                'title' => '*' . $command->getQuery() . '*',
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $model = $this->repositoryElastic->pageByCreatedAt($command->getPage(), $command->getLimit(), $query);

        return $model;
    }
}
