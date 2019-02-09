<?php

namespace App\Domain\User\Repository;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\User;

/**
 * Interface UserRepositoryInterface.
 */
interface UserRepositoryInterface
{
    /**
     * @param AggregateRootId $id
     *
     * @return User
     */
    public function get(AggregateRootId $id): User;

    /**
     * @param User $user
     */
    public function store(User $user): void;
}
