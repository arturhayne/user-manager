<?php

declare(strict_types=1);

namespace Tests\System;

use App\Container;
use PHPUnit\Framework\TestCase;
use UserManager\Application\UserValuesCommand;
use UserManager\Application\UserValuesCommandHandler;
use UserManager\Domain\Exception\InvalidFieldsException;

class UserValuesCommandHandlerTest extends TestCase
{
    public function testEmployeeidShouldBeUniqueAcrossPopulation()
    {
        $container = new Container();
        $handler = $container->get(UserValuesCommandHandler::class);

        $command = UserValuesCommand::createFromArray(1, $this->request(['employeeid' => 'A00513']));

        $this->expectException(InvalidFieldsException::class);
        $this->expectExceptionMessage('{"employeeid":{"isUniqueAcrossPopulation":"Employeeid is not unique across population."}}');

        $handler->execute($command);
    }

    public function testEmailShouldBeUniqueAcrossPopulation()
    {
        $container = new Container();
        $handler = $container->get(UserValuesCommandHandler::class);

        $command = UserValuesCommand::createFromArray(1, $this->request(['email' => 'jdoe@example.com']));

        $this->expectException(InvalidFieldsException::class);
        $this->expectExceptionMessage('{"email":{"isUniqueAcrossPopulation":"Email is not unique across population."}}');

        $handler->execute($command);
    }

    public function testSouldValidateSuccess()
    {
        $container = new Container();
        $handler = $container->get(UserValuesCommandHandler::class);

        $command = UserValuesCommand::createFromArray(1, $this->request(['email' => 'jdoe@example.com']));

        $this->expectException(InvalidFieldsException::class);
        $this->expectExceptionMessage('{"email":{"isUniqueAcrossPopulation":"Email is not unique across population."}}');

        $handler->execute($command);
        $this->expectNotToPerformAssertions();
    }

    private function request(array $custom = []): array
    {
        return [
            'employeeid' => $custom['employeeid'] ?? 'A00510',
            'fname' => 'Jhon',
            'lname' => 'Doe',
            'bdate' => '1994-01-23',
            'bplace' => 'Carignan, QC, Canada',
            'email' => $custom['email'] ?? 'newemail@example.com',
        ];
    }
}
