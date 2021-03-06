<?php

/** @noinspection PhpUndefinedFieldInspection */

namespace App\Infrastructure\Notification;

use App\Infrastructure\User\Query\Projections\UserView;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
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
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
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
     * @param \Ratchet\Wamp\Topic|string $topic
     */
    public function onUnSubscribe(ConnectionInterface $conn, $topic)
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
        dump('unset' . $user->getId());
        unset($this->subscribedTopics[$user->getId()]);
    }

    public function onOpen(ConnectionInterface $conn)
    {
    }

    public function onClose(ConnectionInterface $conn)
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
        dump('unset' . $user->getId());
        unset($this->subscribedTopics[$user->getId()]);
    }

    /**
     * @param string                     $id
     * @param \Ratchet\Wamp\Topic|string $topic
     */
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    /**
     * @param \Ratchet\Wamp\Topic|string $topic
     * @param string                     $event
     */
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onNotify($entry)
    {
        dump(array_keys($this->subscribedTopics));
        $entryData = json_decode($entry, true);

        try {
            /** @var Topic $topic */
            $topic = $this->subscribedTopics[$entryData['user']];
            dump($topic->count());
            // re-send the data to all the clients subscribed to that category
            $topic->broadcast($entryData);
        } catch (\Exception $exception) {
            dump($exception->getMessage());
        }
    }
}
