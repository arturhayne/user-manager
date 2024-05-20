<?php

declare(strict_types=1);

namespace UserManager\Application;

use UserManager\Application\Exception\NotFoundException;
use UserManager\Application\Exception\UserNameAlreadyExistsException;
use UserManager\Domain\Population;
use UserManager\Domain\User;
use UserManager\Domain\UserValuesValidationService;
use UserManager\Infrastructure\PopulationRepository;
use UserManager\Infrastructure\UserRepository;

class UserCommandHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private PopulationRepository $populationRepository,
        private UserValuesValidationService $validatorService
    ) {
    }

    public function execute(UserCommand $command)
    {
        $population = $this->populationRepository->find($command->getPopulationId());

        if (!$population instanceof Population) {
            throw new NotFoundException();
        }

        if ($this->userRepository->hasUser($command->getUserName())) {
            throw new UserNameAlreadyExistsException($command->getUserName());
        }

        $this->validatorService->validate($command->getFields(), $population->getPopulationFields());

        $user = User::create(
            $command->getUserName(),
            $command->getPassword(),
            $population
        );

        $user->setUserValuesFromArray($command->getFields());

        return $this->userRepository->save($user);
    }
}
