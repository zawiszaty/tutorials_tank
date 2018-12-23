<?php

namespace App\Domain\User\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\ValueObject\Email;

/**
 * Class UserMailWasChanged
 * @package App\Domain\User\Event
 */
class UserMailWasChanged extends AbstractEvent
{
    /**
     * @var AggregateRootId
     */
    protected $id;

    /**
     * @var Email
     */
    protected $email;

    /**
     * UserMailWasChanged constructor.
     *
     * @param AggregateRootId $id
     * @param Email           $email
     */
    public function __construct(AggregateRootId $id, Email $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    /**
     * @return AggregateRootId
     */
    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @param array $data
     *
     * @return UserMailWasChanged|mixed
     *
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        return new self(
            AggregateRootId::fromString($data['id']),
            Email::fromString($data['email'])
        );
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id'       => $this->id->toString(),
            'email'    => $this->email->toString(),
        ];
    }
}
