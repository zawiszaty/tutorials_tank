<?php

namespace App\Domain\User\Repository;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\User\User;

interface UserRepositoryInterface
{
    public function get(AggregateRootId $id): User;

    public function store(User $user): void;
}
