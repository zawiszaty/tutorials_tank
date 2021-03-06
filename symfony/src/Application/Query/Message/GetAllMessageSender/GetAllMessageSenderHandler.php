<?php

namespace App\Application\Query\Message\GetAllMessageSender;

use App\Application\Query\Collection;
use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Message\Query\MessageRepositoryElastic;

/**
 * Class GetAllMessageSenderHandler.
 */
class GetAllMessageSenderHandler implements QueryHandlerInterface
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
     */
    public function __construct(MessageRepositoryElastic $repositoryElastic, DataBuilder $dataBuilder)
    {
        $this->repositoryElastic = $repositoryElastic;
        $this->dataBuilder = $dataBuilder;
    }

    public function __invoke(GetAllMessageSenderCommand $command): Collection
    {
        $query = [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'match' => [
                                    'recipient' => $command->getUser(),
                                ],
                            ],
                            [
                                'match' => [
                                    'sender' => $command->getUser(),
                                ],
                            ],
                        ],
                    ],
                ],
            ];

        $data = $this->repositoryElastic->messageByCreatedAt($command->getPage(), 10000, $query);
        $total = $data->total;
        $data = $this->dataBuilder->build($data->data, $command->getUser());

        return new Collection($command->getPage(), $command->getLimit(), $total, $data);
    }
}
