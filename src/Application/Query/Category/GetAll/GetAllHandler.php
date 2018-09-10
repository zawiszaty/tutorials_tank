<?php

namespace App\Application\Query\Category\GetAll;

use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Category\Query\Mysql\MysqlCategoryReadModelRepository;

/**
 * Class GetAllHandler.
 */
class GetAllHandler implements QueryHandlerInterface
{
    /**
     * @var MysqlCategoryReadModelRepository
     */
    private $modelRepository;

    /**
     * GetAllHandler constructor.
     *
     * @param MysqlCategoryReadModelRepository $modelRepository
     */
    public function __construct(MysqlCategoryReadModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }

    /**
     * @param GetAllCommand $command
     *
     * @return \App\Application\Query\Collection
     */
    public function __invoke(GetAllCommand $command)
    {
        $data = $this->modelRepository->getAll($command->getPage(), $command->getLimit());

        return $data;
    }
}
