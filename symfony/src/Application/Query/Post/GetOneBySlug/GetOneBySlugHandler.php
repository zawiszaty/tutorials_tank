<?php

namespace App\Application\Query\Post\GetOneBySlug;

use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Post\Query\Repository\PostRepositoryElastic;

/**
 * Class GetOneBySlugHandler.
 */
class GetOneBySlugHandler implements QueryHandlerInterface
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
     */
    public function __invoke(GetOneBySlugCommand $command)
    {
        $model = $this->repositoryElastic->getOneBySlug($command->slug);

        return $model;
    }
}
