<?php

declare(strict_types=1);

namespace UserManager\Application;

use UserManager\Application\Exception\NotFoundException;
use UserManager\Domain\User;
use UserManager\Domain\UserValuesValidationService;
use UserManager\Infrastructure\UserRepository;

class UserValuesCommandHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private UserValuesValidationService $validatorService
    ) {
    }

    public function execute(UserValuesCommand $command)
    {
        $user = $this->userRepository->find($command->getUserId());

        if (!$user instanceof User) {
            throw new NotFoundException();
        }

        $this->validatorService->validate($command->getFields(), $user->getPopulationFields());
    }
}
