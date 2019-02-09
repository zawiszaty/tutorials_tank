<?php

/** @noinspection PhpUndefinedFieldInspection */

namespace App\Infrastructure\Notification;

use App\Infrastructure\User\Query\Projections\UserView;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * Class Notification.
 */
class Notification implements WampServerInterface
{
    protected $subscribedTopics = [];

    protected $container;

    /**
     * Notification constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ConnectionInterface        $conn
     * @param \Ratchet\Wamp\Topic|string $topic
     */
    public function onSubscribe(ConnectionInterface $conn, $topic)
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
        $user = $token->getUser();
        dump($user->getId());
        $this->subscribedTopics[$user->getId()] = $topic;
    }

    /**
     * @param ConnectionInterface        $conn
     * @param \Ratchet\Wamp\Topic|string $topic
     */
    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
    }

    /**
     * @param ConnectionInterface        $conn
     * @param string                     $id
     * @param \Ratchet\Wamp\Topic|string $topic
     * @param array                      $params
     */
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    /**
     * @param ConnectionInterface        $conn
     * @param \Ratchet\Wamp\Topic|string $topic
     * @param string                     $event
     * @param array                      $exclude
     * @param array                      $eligible
     */
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception          $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onNotify($entry)
    {
        $entryData = json_decode($entry, true);

        try {
            $topic = $this->subscribedTopics[$entryData['user']];
            // re-send the data to all the clients subscribed to that category
            $topic->broadcast($entryData);
        } catch (\Exception $exception) {
            dump($exception->getMessage());
        }
    }
}