<?php

namespace App\Infrastructure\Notification;

use App\Infrastructure\User\Query\Projections\UserView;
use Ramsey\Uuid\Uuid;

/**
 * Class NotificationFactory.
 */
class NotificationFactory
{
    /**
     * @throws \Exception
     */
    public static function create(string $content, UserView $user, string $type): NotificationView
    {
        $notification = new NotificationView(
            Uuid::uuid4(),
            $content,
            false,
            $user,
            $type,
            new \DateTime()
        );

        return $notification;
    }
}
