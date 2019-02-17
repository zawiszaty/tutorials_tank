<?php

namespace App\Application\Query\Notification\GetAllByUser;

use App\Application\Query\Collection;
use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Notification\Query\NotificationRepositoryElastic;

/**
 * Class GetAllByUserHandler.
 */
class GetAllByUserHandler implements QueryHandlerInterface
{
    /**
     * @var NotificationRepositoryElastic
     */
    private $notificationRepositoryElastic;

    public function __construct(NotificationRepositoryElastic $notificationRepositoryElastic)
    {
        $this->notificationRepositoryElastic = $notificationRepositoryElastic;
    }

    public function __invoke(GetAllByUserCommand $command): Collection
    {
        $query = [
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'match' => [
                                'user' => $command->getQuery(),
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $data = $this->notificationRepositoryElastic->getByDynamicBody($command->getPage(), $command->getLimit(), $query, $command->getSort());

        return $data;
    }
}
