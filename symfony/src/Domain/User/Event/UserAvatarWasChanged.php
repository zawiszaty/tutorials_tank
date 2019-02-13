<?php

namespace App\Domain\User\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\ValueObject\Avatar;

/**
 * Class UserAvatarWasChanged.
 */
class UserAvatarWasChanged extends AbstractEvent
{
    /**
     * @var AggregateRootId
     */
    protected $id;

    /**
     * @var Avatar
     */
    private $avatar;

    /**
     * UserMailWasChanged constructor.
     */
    public function __construct(AggregateRootId $id, Avatar $avatar)
    {
        $this->id = $id;
        $this->avatar = $avatar;
    }

    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    public function getAvatar(): Avatar
    {
        return $this->avatar;
    }

    /**
     * @throws \Assert\AssertionFailedException
     *
     * @return UserMailWasChanged|mixed
     */
    public static function deserialize(array $data)
    {
        return new self(
            AggregateRootId::fromString($data['id']),
            Avatar::fromString($data['avatar'])
        );
    }

    public function serialize(): array
    {
        return [
            'id'     => $this->id->toString(),
            'avatar' => $this->avatar->toString(),
        ];
    }
}
