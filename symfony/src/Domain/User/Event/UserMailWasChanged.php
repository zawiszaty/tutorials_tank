<?php

namespace App\Domain\User\Event;

use App\Domain\Common\Event\AbstractEvent;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\ValueObject\Email;

/**
 * Class UserMailWasChanged.
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
     */
    public function __construct(AggregateRootId $id, Email $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @throws \Assert\AssertionFailedException
     *
     * @return UserMailWasChanged|mixed
     */
    public static function deserialize(array $data): self
    {
        return new self(
            AggregateRootId::fromString($data['id']),
            Email::fromString($data['email'])
        );
    }

    public function serialize(): array
    {
        return [
            'id'    => $this->id->toString(),
            'email' => $this->email->toString(),
        ];
    }
}
