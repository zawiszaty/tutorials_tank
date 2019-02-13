<?php

namespace App\Domain\User\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\ValueObject\Password;

/**
 * Class UserPasswordWasChanged.
 */
class UserPasswordWasChanged extends AbstractEvent
{
    /**
     * @var AggregateRootId
     */
    protected $id;

    /**
     * @var Password
     */
    protected $password;

    /**
     * UserMailWasChanged constructor.
     */
    public function __construct(AggregateRootId $id, Password $password)
    {
        $this->id = $id;
        $this->password = $password;
    }

    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    public function getPassword(): Password
    {
        return $this->password;
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
            password::fromString($data['password'])
        );
    }

    public function serialize(): array
    {
        return [
            'id'       => $this->id->toString(),
            'password' => $this->password->toString(),
        ];
    }
}
