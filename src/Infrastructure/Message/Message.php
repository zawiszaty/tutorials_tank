<?php

namespace App\Infrastructure\Message;

use App\Infrastructure\User\Query\Projections\UserView;
use Ramsey\Uuid\Uuid;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class Message implements MessageComponentInterface
{
    protected $connections = [];

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $querystring = $conn->httpRequest->getUri()->getQuery();
        parse_str($querystring, $queryarray);

        if (!$queryarray['token']) {
            throw new AccessDeniedException('Brak tokena');
        }

        $token = $this->container->get('doctrine')->getRepository('App:AccessToken')->findOneBy([
            'token' => $queryarray['token'],
        ]);

        if ($token->getExpiresIn() < 0) {
            throw new AccessDeniedException('Token wygasł');
        }
        /** @var UserView $sender */
        $sender = $token->getUser();

        $this->connections[$sender->getId()] = $conn;
        $conn->send('..:: Hello from the Notification Center ::..');
        echo "New connection \n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        foreach ($this->connections as $key => $conn_element) {
            if ($conn === $conn_element) {
                unset($this->connections[$key]);

                break;
            }
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->send('Error : '.$e->getMessage());
        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $messageData = json_decode(trim($msg));
        if ($messageData->userData->token) {
            $token = $this->container->get('doctrine')->getRepository('App:AccessToken')->findOneBy([
                'token' => $messageData->userData->token,
            ]);

            if ($token->getExpiresIn() < 0) {
                throw new AccessDeniedException('Token wygasł');
            }

            $recipient = $this->container->get('doctrine')->getRepository('Projections:User\Query\Projections\UserView')->find($messageData->userData->recipient);
            $sender = $this->container->get('doctrine')->getRepository('Projections:User\Query\Projections\UserView')->find($token->getUser()->getId());

            $message = new MessageView(
                Uuid::uuid4(),
                $messageData->userData->content,
                false,
                $sender,
                $recipient,
                new \DateTime()
            );
            $em = $this->container->get('doctrine')->getManager();
            $em->persist($message);
            $em->flush();

            try {
                $this->connections[$recipient->getId()]->send(json_encode([
                    'id' => $message->getId(),
                    'content' => $message->getContent(),
                    'sender' => [
                        'id' => $sender->getId(),
                        'username' => $sender->getUsername(),
                        'avatar' => $sender->getAvatar(),
                    ],
                    'recipient' => [
                        'id' => $recipient->getId(),
                        'username' => $recipient->getUsername(),
                        'avatar' => $recipient->getAvatar(),
                    ],
                    'createdAt' => $message->getCreatedAt(),
                ]));
            } catch (\Exception $exception) {
            }
        }
    }
}
