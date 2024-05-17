<?php

declare(strict_types=1);

namespace UserManager\Tests\Application;

use PHPUnit\Framework\TestCase;
use UserManager\Application\Exception\NotFoundException;
use UserManager\Application\Exception\UserNameAlreadyExistsException;
use UserManager\Application\UserCommand;
use UserManager\Application\UserCommandHandler;
use UserManager\Domain\Population;
use UserManager\Infrastructure\PopulationRepository;
use UserManager\Infrastructure\UserRepository;

class UserCommandHandlerTest extends TestCase
{
    public function testExecuteCreatesUserSuccessfully(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $populationRepositoryMock = $this->createMock(PopulationRepository::class);

        $populationId = '1';
        $populationMock = $this->createMock(Population::class);
        $populationRepositoryMock->expects($this->once())
            ->method('find')
            ->with($populationId)
            ->willReturn($populationMock);

        $userRepositoryMock->expects($this->once())
            ->method('hasUser')
            ->willReturn(false);

        $handler = new UserCommandHandler($userRepositoryMock, $populationRepositoryMock);

        $userData = [
            'user_name' => 'test_user',
            'password' => 'test_password',
        ];
        $command = UserCommand::create($populationId, $userData);

        $result = $handler->execute($command);

        $this->assertIsInt($result);
    }

    public function testExecuteThrowsNotFoundExceptionWhenPopulationNotFound(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $populationRepositoryMock = $this->createMock(PopulationRepository::class);

        $populationId = '1';
        $populationRepositoryMock->expects($this->once())
            ->method('find')
            ->with($populationId)
            ->willReturn(null);

        $handler = new UserCommandHandler($userRepositoryMock, $populationRepositoryMock);

        $userData = [
            'user_name' => 'test_user',
            'password' => 'test_password',
        ];
        $command = UserCommand::create($populationId, $userData);

        $this->expectException(NotFoundException::class);
        $handler->execute($command);
    }

    public function testExecuteThrowsUserNameAlreadyExistsExceptionWhenUserExists(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $populationRepositoryMock = $this->createMock(PopulationRepository::class);

        $populationId = '1';
        $populationMock = $this->createMock(Population::class);
        $populationRepositoryMock->expects($this->once())
            ->method('find')
            ->with($populationId)
            ->willReturn($populationMock);

        $userName = 'existing_user';
        $userRepositoryMock->expects($this->once())
            ->method('hasUser')
            ->willReturn(true);

        $handler = new UserCommandHandler($userRepositoryMock, $populationRepositoryMock);

        $userData = [
            'user_name' => $userName,
            'password' => 'test_password',
        ];
        $command = UserCommand::create($populationId, $userData);

        $this->expectException(UserNameAlreadyExistsException::class);
        $handler->execute($command);
    }
}
