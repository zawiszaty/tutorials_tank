<?php

namespace App\Application\Query\Category\GetAll;

use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Category\Query\Mysql\MysqlCategoryReadModelRepository;

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
     * GetAllHandler constructor.
     * @param MysqlCategoryReadModelRepository $modelRepository
     */
    public function __construct(MysqlCategoryReadModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }

    /**
     * @param GetAllCommand $command
     * @return \Doctrine\ORM\Query
     */
    public function __invoke(GetAllCommand $command)
    {
       $model = $this->modelRepository->getAll()->execute();

       return $model;
    }
}