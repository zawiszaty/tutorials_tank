<?php

namespace App\Infrastructure\Notification\Strategy;

/**
 * Interface NotificationStrategyInterface
 *
 * @package App\Infrastructure\Notification\Strategy
 */
interface NotificationStrategyInterface
{
    /**
     * @param array $data
     *
     * @return mixed
     */
    public static function notify(array $data);
}
