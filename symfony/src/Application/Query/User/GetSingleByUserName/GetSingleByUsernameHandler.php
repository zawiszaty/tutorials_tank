<?php

namespace App\Application\Query\User\GetSingleByUserName;

use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\User\Query\Projections\UserView;
use App\Infrastructure\User\Repository\UserRepositoryElastic;

class GetSingleByUsernameHandler implements QueryHandlerInterface
{
    /**
     * @var UserRepositoryElastic
     */
    private $userRepositoryElastic;

    public function __construct(UserRepositoryElastic $userRepositoryElastic)
    {
        $this->userRepositoryElastic = $userRepositoryElastic;
    }

    /**
     * @return UserView
     */
    public function __invoke(GetSingleByUsernameCommand $command): array
    {
        $query = [
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'match' => [
                                'username' => $command->username,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $user = $this->userRepositoryElastic->getByDynamicBody(1, 1, $query)->data[0];

        return $user;
    }
}
