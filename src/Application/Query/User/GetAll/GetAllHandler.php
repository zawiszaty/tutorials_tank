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
        $data = $this->repositoryElastic->page($command->getPage(), $command->getLimit(), $command->getQuery());

        return $data;
    }
}
