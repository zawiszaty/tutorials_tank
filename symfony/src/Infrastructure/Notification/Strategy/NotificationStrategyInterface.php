<?php

namespace App\Infrastructure\Notification\Strategy;

/**
 * Interface NotificationStrategyInterface.
 */
interface NotificationStrategyInterface
{
    public function notify(array $data);
}
