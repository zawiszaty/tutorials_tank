<?php

namespace App\Infrastructure\Notification;

use App\Infrastructure\User\Query\Projections\UserView;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class Notification implements WampServerInterface
{
    protected $subscribedTopics = [];

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onSubscribe(ConnectionInterface $conn, $topic) {
        $querystring = $conn->httpRequest->getUri()->getQuery();
        parse_str($querystring, $queryarray);
        dump($topic);

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
        $user = $token->getUser();
        $this->subscribedTopics[$topic->getId()][$user->getId()] = $topic;
    }
    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
    }
    public function onOpen(ConnectionInterface $conn) {
    }
    public function onClose(ConnectionInterface $conn) {
    }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onNotify($entry) {
        $entryData = json_decode($entry, true);

        $topic = $this->subscribedTopics['user'][$entryData['user']];

        // re-send the data to all the clients subscribed to that category
        $topic->broadcast($entryData);
    }
}