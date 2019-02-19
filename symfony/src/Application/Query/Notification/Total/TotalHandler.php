<?php

namespace App\Application\Query\Notification\Total;

use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Notification\Query\NotificationRepositoryElastic;

/**
 * Class TotalHandler.
 */
class TotalHandler implements QueryHandlerInterface
{
    /**
     * @var NotificationRepositoryElastic
     */
    private $notificationRepositoryElastic;

    public function __construct(NotificationRepositoryElastic $notificationRepositoryElastic)
    {
        $this->notificationRepositoryElastic = $notificationRepositoryElastic;
    }

    public function __invoke(TotalCommand $totalCommand): int
    {
        $query = [
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'match' => [
                                'user' => $totalCommand->user,
                            ],
                        ],
                        [
                            'match' => [
                                'displayed' => false,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $data = $this->notificationRepositoryElastic->getByDynamicBody('1', '1', $query, 'asc');

        return $data->total;
    }
}
