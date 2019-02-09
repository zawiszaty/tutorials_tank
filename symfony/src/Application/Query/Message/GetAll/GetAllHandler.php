<?php

namespace App\Application\Query\Message\GetAll;

use App\Application\Query\Collection;
use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Message\Query\MessageRepositoryElastic;

/**
 * Class GetAllHandler.
 */
class GetAllHandler implements QueryHandlerInterface
{
    /**
     * @var MessageRepositoryElastic
     */
    private $repositoryElastic;

    /**
     * @var DataBuilder
     */
    private $dataBuilder;

    /**
     * GetAllHandler constructor.
     *
     * @param MessageRepositoryElastic $repositoryElastic
     * @param DataBuilder              $dataBuilder
     */
    public function __construct(MessageRepositoryElastic $repositoryElastic, DataBuilder $dataBuilder)
    {
        $this->repositoryElastic = $repositoryElastic;
        $this->dataBuilder = $dataBuilder;
    }

    /**
     * @param GetAllCommand $command
     *
     * @return Collection
     */
    public function __invoke(GetAllCommand $command)
    {
        if ($command->getQuery()) {
            $query = [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'match' => [
                                    'recipient' => $command->getRecipient(),
                                ],
                            ],
                            [
                                'match' => [
                                    'recipient' => $command->getUser(),
                                ],
                            ],
                        ],
                    ],
                ],
            ];
        } else {
            $query = [];
        }
        $data = $this->repositoryElastic->messageByCreatedAt($command->getPage(), $command->getLimit(), $query);
        $total = $data->total;
        $data = $this->dataBuilder->build($data->data);

        return new Collection($command->getPage(), $command->getLimit(), $total, $data);
    }
}
