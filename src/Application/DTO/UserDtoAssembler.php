<?php

declare(strict_types=1);

namespace UserManager\Application\DTO;

use UserManager\Domain\User;

interface UserDtoAssembler
{
    public function assemble(User $user): mixed;
}
