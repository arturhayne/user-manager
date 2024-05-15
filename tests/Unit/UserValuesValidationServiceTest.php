<?php

declare(strict_types=1);

namespace Tests\Unit;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use UserManager\Domain\Exception\InvalidFieldsException;
use UserManager\Domain\PopulationField;
use UserManager\Domain\UserValuesValidationService;
use UserManager\Domain\Validator;
use UserManager\Infrastructure\UserValuesRepository;

class UserValuesValidationServiceTest extends TestCase
{
    public function testShouldValidateUniqueFields()
    {
        $repositoryMock = $this->createMock(UserValuesRepository::class);
        $repositoryMock->method('isUnique')->willReturn(false);

        $validator = new Validator($repositoryMock);

        $field = new PopulationField(1, 'text', true, true, false, false, 'employeeid', 'Employee ID', 1, false);

        $rules = new ArrayCollection([$field]);

        $service = new UserValuesValidationService($validator);

        $fields = [
            'employeeid' => 'A005132',
        ];

        $this->expectException(InvalidFieldsException::class);
        $this->expectExceptionMessage('{"employeeid":{"isUnique":"Employeeid is not unique."}}');

        $service->validate($fields, $rules);
    }

    public function testShouldPassNonUniqueFields()
    {
        $repositoryMock = $this->createMock(UserValuesRepository::class);
        $repositoryMock->method('isUnique')->willReturn(true);

        $validator = new Validator($repositoryMock);

        $field = new PopulationField(1, 'text', true, true, false, false, 'employeeid', 'Employee ID', 1, false);

        $rules = new ArrayCollection([$field]);

        $service = new UserValuesValidationService($validator);

        $fields = [
            'employeeid' => 'A005132',
        ];

        $service->validate($fields, $rules);
        $this->expectNotToPerformAssertions();
    }

    public function testShouldValidateUniqueAcrossPopulation()
    {
        $repositoryMock = $this->createMock(UserValuesRepository::class);
        $repositoryMock->method('isUniqueAcrossPopulation')->willReturn(false);

        $validator = new Validator($repositoryMock);

        $field = new PopulationField(1, 'text', true, true, false, false, 'employeeid', 'Employee ID', 1, true);

        $rules = new ArrayCollection([$field]);

        $service = new UserValuesValidationService($validator);

        $fields = [
            'employeeid' => 'A005132',
        ];

        $this->expectException(InvalidFieldsException::class);
        $this->expectExceptionMessage('{"employeeid":{"isUniqueAcrossPopulation":"Employeeid is not unique across population."}}');

        $service->validate($fields, $rules);
    }

    public function testShouldPassNonUniqueAcrossPopulation()
    {
        $repositoryMock = $this->createMock(UserValuesRepository::class);
        $repositoryMock->method('isUniqueAcrossPopulation')->willReturn(true);

        $validator = new Validator($repositoryMock);

        $field = new PopulationField(1, 'text', true, true, false, false, 'employeeid', 'Employee ID', 1, true);

        $rules = new ArrayCollection([$field]);

        $service = new UserValuesValidationService($validator);

        $fields = [
            'employeeid' => 'A005132',
        ];

        $service->validate($fields, $rules);

        $this->expectNotToPerformAssertions();
    }

    public function testWithoutUniqueValidation()
    {
        $repositoryMock = $this->createMock(UserValuesRepository::class);
        $validator = new Validator($repositoryMock);

        $field = new PopulationField(1, 'text', true, false, false, false, 'employeeid', 'Employee ID', 1, false);

        $rules = new ArrayCollection([$field]);

        $service = new UserValuesValidationService($validator);

        $fields = [
            'employeeid' => 'A005132',
        ];

        $service->validate($fields, $rules);
        $this->expectNotToPerformAssertions();
    }

    public function testEmailValidation()
    {
        $repositoryMock = $this->createMock(UserValuesRepository::class);
        $validator = new Validator($repositoryMock);

        $field = new PopulationField(1, 'email', true, false, false, false, 'email', 'Email Address', 1, false);

        $rules = new ArrayCollection([$field]);

        $service = new UserValuesValidationService($validator);

        $fields = [
            'email' => 'invalid-email',
        ];

        $this->expectException(InvalidFieldsException::class);
        $this->expectExceptionMessage('{"email":{"email":"Email is invalid email."}}');

        $service->validate($fields, $rules);
    }

    public function testDateValidation()
    {
        $repositoryMock = $this->createMock(UserValuesRepository::class);
        $validator = new Validator($repositoryMock);

        $field = new PopulationField(1, 'date', true, false, false, false, 'bdate', 'Birthday date', 1, false);

        $rules = new ArrayCollection([$field]);

        $service = new UserValuesValidationService($validator);

        $fields = [
            'bdate' => 'invalid-date',
        ];

        $this->expectException(InvalidFieldsException::class);
        $this->expectExceptionMessage('{"bdate":{"date":"Bdate is invalid date."}}');

        $service->validate($fields, $rules);
    }
}
