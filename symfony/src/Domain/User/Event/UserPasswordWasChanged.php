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
     *
     * @param AggregateRootId $id
     * @param password        $password
     */
    public function __construct(AggregateRootId $id, Password $password)
    {
        $this->id = $id;
        $this->password = $password;
    }

    /**
     * @return AggregateRootId
     */
    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    /**
     * @return Password
     */
    public function getPassword(): Password
    {
        return $this->password;
    }

    /**
     * @param array $data
     *
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

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id'       => $this->id->toString(),
            'password' => $this->password->toString(),
        ];
    }
}
