<?php

namespace App\Application\Query\Message\GetAllMessageSender;

/**
 * Class SenderFlyweight.
 */
class SenderFlyweight
{
    /**
     * @var array[]
     */
    private static $sender = [];

    public static function addSender(string $sender): void
    {
        if (!\in_array($sender, self::$sender)) {
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
