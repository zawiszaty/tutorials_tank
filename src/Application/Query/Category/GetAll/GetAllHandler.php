<?php

namespace App\Application\Query\Category\GetAll;

use App\Application\Query\Collection;
use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Category\Query\Mysql\MysqlCategoryReadModelRepository;
use App\Infrastructure\Category\Repository\CategoryRepositoryElastic;
use App\Infrastructure\Share\Query\Repository\ElasticRepository;

/**
 * Class GetAllHandler
 * @package App\Application\Query\Category\GetAll
 */
class GetAllHandler implements QueryHandlerInterface
{
    /**
     * @var MysqlCategoryReadModelRepository
     */
    private $modelRepository;
    /**
     * @var ElasticRepository
     */
    private $elasticRepository;

    /**
     * GetAllHandler constructor.
     * @param MysqlCategoryReadModelRepository $modelRepository
     */
    public function __construct(MysqlCategoryReadModelRepository $modelRepository, CategoryRepositoryElastic $elasticRepository)
    {
        $this->modelRepository = $modelRepository;
        $this->elasticRepository = $elasticRepository;
    }

    /**
     * @param GetAllCommand $command
     * @return array
     */
    public function __invoke(GetAllCommand $command)
    {
//        $collection = $this->modelRepository->getAll();
        $data = $this->elasticRepository->page();
//        $collection = new Collection($data);

        return $data;
    }
}