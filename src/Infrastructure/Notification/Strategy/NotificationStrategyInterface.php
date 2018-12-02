<?php

namespace App\Infrastructure\Notification\Strategy;

interface NotificationStrategyInterface
{
    public static function notify(array $data);
}