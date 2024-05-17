<?php

declare(strict_types=1);

namespace UserManager\Infrastructure;

use UserManager\Domain\User;

interface UserRepository
{
    public function save(User $user): int;

    public function hasUser(string $username): bool;

    public function find(int $userId): ?User;
}
