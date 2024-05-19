<?php

declare(strict_types=1);

namespace UserManager\Application;

use UserManager\Domain\User;
use UserManager\Infrastructure\UserRepository;
use UserManager\Application\DTO\UserDtoAssembler;
use UserManager\Application\Exception\NotFoundException;

class UserQueryHandler
{
    public function __construct(
        private UserRepository $repository,
        private UserDtoAssembler $dtoAssembler,
    ) {
    }

    public function execute(int $id)
    {
        $user = $this->repository->find($id);

        if (!$user instanceof User) {
            throw new NotFoundException();
        }

        return $this->dtoAssembler->assemble($user);
    }
}
