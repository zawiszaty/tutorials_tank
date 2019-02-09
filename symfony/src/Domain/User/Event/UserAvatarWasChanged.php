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
     *
     * @param AggregateRootId $id
     * @param Avatar          $avatar
     */
    public function __construct(AggregateRootId $id, Avatar $avatar)
    {
        $this->id = $id;
        $this->avatar = $avatar;
    }

    /**
     * @return AggregateRootId
     */
    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    /**
     * @return Avatar
     */
    public function getAvatar(): Avatar
    {
        return $this->avatar;
    }

    /**
     * @param array $data
     *
     * @return UserMailWasChanged|mixed
     *
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data)
    {
        return new self(
            AggregateRootId::fromString($data['id']),
            Avatar::fromString($data['avatar'])
        );
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id'     => $this->id->toString(),
            'avatar' => $this->avatar->toString(),
        ];
    }
}
