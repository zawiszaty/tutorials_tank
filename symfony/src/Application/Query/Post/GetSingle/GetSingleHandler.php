<?php

namespace App\Application\Query\Post\GetSingle;

use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Post\Query\Repository\PostRepositoryElastic;

/**
 * Class GetSingleHandler.
 */
class GetSingleHandler implements QueryHandlerInterface
{
    /**
     * @var PostRepositoryElastic
     */
    private $repositoryElastic;

    /**
     * GetSingleHandler constructor.
     */
    public function __construct(PostRepositoryElastic $repositoryElastic)
    {
        $this->repositoryElastic = $repositoryElastic;
    }

    public function __invoke(GetSingleCommand $command)
    {
        $model = $this->repositoryElastic->get($command->getId());

        return $model['_source'];
    }
}
