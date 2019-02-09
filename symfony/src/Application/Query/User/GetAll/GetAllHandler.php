<?php

namespace App\Application\Query\User\GetAll;

use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\User\Repository\UserRepositoryElastic;

/**
 * Class GetAllHandler.
 */
class GetAllHandler implements QueryHandlerInterface
{
    /**
     * @var UserRepositoryElastic
     */
    private $repositoryElastic;

    /**
     * GetAllHandler constructor.
     *
     * @param UserRepositoryElastic $repositoryElastic
     */
    public function __construct(UserRepositoryElastic $repositoryElastic)
    {
        $this->repositoryElastic = $repositoryElastic;
    }

    /**
     * @param GetAllCommand $command
     *
     * @return \App\Application\Query\Collection
     */
    public function __invoke(GetAllCommand $command)
    {
        if ($command->getQuery()) {
            $query = [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'wildcard' => [
                                    'username' => '*'.$command->getQuery().'*',
                                ],
                            ],
                            [
                                'wildcard' => [
                                    'email' => '*'.$command->getQuery().'*',
                                ],
                            ],
                        ],
                    ],
                ],
            ];
        } else {
            $query = [];
        }
        $data = $this->repositoryElastic->page($command->getPage(), $command->getLimit(), $query);

        return $data;
    }
}
