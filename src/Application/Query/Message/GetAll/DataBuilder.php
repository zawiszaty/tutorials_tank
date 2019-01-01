<?php

namespace App\Application\Query\Message\GetAll;

use App\Infrastructure\User\Query\Projections\UserView;
use App\Infrastructure\User\Repository\UserRepositoryElastic;

class DataBuilder
{
    /**
     * @var UserRepositoryElastic
     */
    private $repositoryElastic;

    public function __construct(UserRepositoryElastic $repositoryElastic)
    {
        $this->repositoryElastic = $repositoryElastic;
    }

    public function build(array $data): array
    {
        foreach ($data as $key => $item) {
            /** @var UserView $sender */
            $sender = $this->repositoryElastic->get($item['sender'])['_source'];
            $data[$key]['sender'] = [
                'username' => $sender['username'],
                'avatar'   => $sender['avatar'],
                'id'       => $sender['id'],
            ];
            /** @var UserView $sender */
            $recipient = $this->repositoryElastic->get($item['recipient']);
            $data[$key]['recipient'] = [
                'username' => $sender['username'],
                'avatar'   => $sender['avatar'],
                'id'       => $sender['id'],
            ];
        }

        return $data;
    }
}
