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
     * @var array
     */
    private $sender = [];

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
                SenderFlyweight::addSender($item['sender']);
            } elseif ($item['recipient'] !== $userId) {
                SenderFlyweight::addSender($item['recipient']);
            }
        }
        foreach (SenderFlyweight::getSender() as $sender) {
            $query = [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'bool' => [
                                    'must' => [
                                        [
                                            'match' => [
                                                'recipient' => $sender,
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
                            [
                                'bool' => [
                                    'must' => [
                                        [
                                            'match' => [
                                                'recipient' => $userId,
                                            ],
                                        ],
                                        [
                                            'match' => [
                                                'sender' => $sender,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ];
            $messages = $this->messageRepositoryElastic->messageByCreatedAt(1, 1, $query);
            $user = $this->repositoryElastic->get($sender)['_source'];
            $user['displayed'] = $messages->data[0]['displayed'];
            $this->sender[] = $user;
        }

        return $this->sender;
    }
}
