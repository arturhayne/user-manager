<?php

declare(strict_types=1);

namespace UserManager\Application;

use UserManager\Application\Exception\NotFoundException;
use UserManager\Application\Exception\UserNameAlreadyExistsException;
use UserManager\Domain\Population;
use UserManager\Domain\User;
use UserManager\Infrastructure\PopulationRepository;
use UserManager\Infrastructure\UserRepository;

class UserCommandHandler
{
    public function __construct(private UserRepository $userRepository, private PopulationRepository $populationRepository)
    {
    }

    public function execute(UserCommand $command)
    {
        $population = $this->populationRepository->find($command->getPopulationId());

        if (!$population instanceof Population) {
            throw new NotFoundException();
        }

        if ($this->userRepository->hasUser($command->getUserName())) {
            var_dump('testeee');
            throw new UserNameAlreadyExistsException($command->getUserName());
        }

        $user = User::create(
            $command->getUserName(),
            $command->getPassword(),
            $population
        );

        return $this->userRepository->save($user);
    }
}
