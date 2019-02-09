<?php

namespace App\Infrastructure\Notification\Strategy;

/**
 * Interface NotificationStrategyInterface.
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
