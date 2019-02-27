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
        if (count(self::$sender) === 0) {
            self::$sender[] = $sender;
        } else {
            foreach (self::$sender as $item) {
                if ($item['id'] === $sender['id']) {
                    break 1;
                } else {
                    self::$sender[] = $sender;
                }
            }
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