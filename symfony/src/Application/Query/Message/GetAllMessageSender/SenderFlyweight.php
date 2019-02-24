<?php

namespace App\Application\Query\Message\GetAllMessageSender;

/**
 * Class SenderFlyweight
 *
 * @package App\Application\Query\Message\GetAllMessageSender
 */
class SenderFlyweight
{
    /**
     * @var array[]
     */
    private static $sender = [];

    /**
     * @param string $sender
     */
    public static function addSender(array $sender): void
    {
        if (!array_key_exists($sender, self::$sender)) {
            self::$sender[] = $sender;
        }
    }

    /**
     * @return array[]
     */
    public static function getSender(): array
    {
        return self::$sender;
    }
}