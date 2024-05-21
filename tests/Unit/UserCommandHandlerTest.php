<?php

declare(strict_types=1);

namespace UserManager\Tests\Application;

use PHPUnit\Framework\TestCase;
use UserManager\Application\Exception\NotFoundException;
use UserManager\Application\Exception\UserNameAlreadyExistsException;
use UserManager\Application\UserCommand;
use UserManager\Application\UserCommandHandler;
use UserManager\Domain\Population;
use UserManager\Domain\PopulationField;
use UserManager\Domain\UserValuesValidationService;
use UserManager\Infrastructure\PopulationRepository;
use UserManager\Infrastructure\UserRepository;

class UserCommandHandlerTest extends TestCase
{
    public function testExecuteCreatesUserSuccessfully(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $populationRepositoryMock = $this->createMock(PopulationRepository::class);
        $validatorServiceMock = $this->createMock(UserValuesValidationService::class);

        $populationId = '1';
        $populationMock = $this->createMock(Population::class);

        $populationFields = new \Doctrine\Common\Collections\ArrayCollection([
            new PopulationField(1, 'text', true, true, false, false, 'employeeid', 'Employee ID', 1, true),
            new PopulationField(2, 'text', true, true, false, false, 'fname', 'First Name', 1, true),
            new PopulationField(3, 'text', true, true, false, false, 'lname', 'Last Name', 1, true),
            new PopulationField(4, 'text', true, true, false, false, 'bdate', 'Birth Date', 1, true),
            new PopulationField(5, 'text', true, true, false, false, 'bplace', 'Birth Place', 1, true),
            new PopulationField(6, 'email', true, true, false, false, 'email', 'Email', 1, true),
        ]);

        $populationMock->expects($this->any())
            ->method('getPopulationFields')
            ->willReturn($populationFields);

        $populationRepositoryMock->expects($this->once())
            ->method('find')
            ->with($populationId)
            ->willReturn($populationMock);

        $userRepositoryMock->expects($this->once())
            ->method('hasUser')
            ->willReturn(false);

        $handler = new UserCommandHandler($userRepositoryMock, $populationRepositoryMock, $validatorServiceMock);

        $userData = [
            'password' => 'password1234',
            'user_name' => 'arturhayne',
            'employeeid' => 'A00513ws',
            'fname' => 'Jhon',
            'lname' => 'Doe',
            'bdate' => '1994-01-23',
            'bplace' => 'Carignan, QC, Canada',
            'email' => 'jdocoal2@example.com',
        ];
        $command = UserCommand::create($populationId, $userData);

        $userRepositoryMock->expects($this->once())
            ->method('save')
            ->willReturn(1);

        $result = $handler->execute($command);

        $this->assertIsInt($result);
    }

    public function testExecuteThrowsNotFoundExceptionWhenPopulationNotFound(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $populationRepositoryMock = $this->createMock(PopulationRepository::class);
        $validatorServiceMock = $this->createMock(UserValuesValidationService::class);

        $populationId = '1';
        $populationRepositoryMock->expects($this->once())
            ->method('find')
            ->with($populationId)
            ->willReturn(null);

        $handler = new UserCommandHandler($userRepositoryMock, $populationRepositoryMock, $validatorServiceMock);

        $userData = [
            'password' => 'password1234',
            'user_name' => 'arturhayne',
            'employeeid' => 'A00513ws',
            'fname' => 'Jhon',
            'lname' => 'Doe',
            'bdate' => '1994-01-23',
            'bplace' => 'Carignan, QC, Canada',
            'email' => 'jdocoal2@example.com',
        ];
        $command = UserCommand::create($populationId, $userData);

        $this->expectException(NotFoundException::class);
        $handler->execute($command);
    }

    public function testExecuteThrowsUserNameAlreadyExistsExceptionWhenUserExists(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $populationRepositoryMock = $this->createMock(PopulationRepository::class);
        $validatorServiceMock = $this->createMock(UserValuesValidationService::class);

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

        $handler = new UserCommandHandler($userRepositoryMock, $populationRepositoryMock, $validatorServiceMock);

        $userData = [
            'password' => 'password1234',
            'user_name' => 'arturhayne',
            'employeeid' => 'A00513ws',
            'fname' => 'Jhon',
            'lname' => 'Doe',
            'bdate' => '1994-01-23',
            'bplace' => 'Carignan, QC, Canada',
            'email' => 'jdocoal2@example.com',
        ];
        $command = UserCommand::create($populationId, $userData);

        $this->expectException(UserNameAlreadyExistsException::class);
        $handler->execute($command);
    }
}
