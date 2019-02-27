<?php

namespace App\Application\Query\Message\GetAllMessageSender;

use App\Infrastructure\Message\Query\MessageRepositoryElastic;
use App\Infrastructure\User\Repository\UserRepositoryElastic;

/**
 * Class DataBuilder.
 */
class DataBuilder
{
    /**
     * @var UserRepositoryElastic
     */
    private $repositoryElastic;

    /**
     * @var MessageRepositoryElastic
     */
    private $messageRepositoryElastic;

    /**
     * DataBuilder constructor.
     */
    public function __construct(UserRepositoryElastic $repositoryElastic, MessageRepositoryElastic $messageRepositoryElastic)
    {
        $this->repositoryElastic = $repositoryElastic;
        $this->messageRepositoryElastic = $messageRepositoryElastic;
    }

    public function build(array $data, string $userId): array
    {
        foreach ($data as $key => $item) {
            if ($item['sender'] !== $userId) {
                $query = [
                    'query' => [
                        'bool' => [
                            'must' => [
                                [
                                    'match' => [
                                        'recipient' => $userId,
                                    ],
                                ],
                                [
                                    'match' => [
                                        'sender' => $item['sender'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ];
                $messages = $this->messageRepositoryElastic->messageByCreatedAt(1, 1, $query);
                $user = $this->repositoryElastic->get($item['sender'])['_source'];
                $user['displayed'] = $messages->data[0]['displayed'];
                SenderFlyweight::addSender($user);
            }
            if ($item['recipient'] !== $userId) {
                $query = [
                    'query' => [
                        'bool' => [
                            'must' => [
                                [
                                    'match' => [
                                        'recipient' => $item['recipient'],
                                    ],
                                ],
                                [
                                    'match' => [
                                        'sender' => $userId,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ];
                $messages = $this->messageRepositoryElastic->messageByCreatedAt(1, 1, $query);
                $user = $this->repositoryElastic->get($item['recipient'])['_source'];
                $user['displayed'] = $messages->data[0]['displayed'];
                SenderFlyweight::addSender($user);
            }
        }

        return SenderFlyweight::getSender();
    }
}
