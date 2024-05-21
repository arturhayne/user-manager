<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use UserManager\Application\DTO\PopulationDtoAssembler;
use UserManager\Application\PopulationQueryHandler;
use UserManager\Domain\Population;
use UserManager\Infrastructure\PopulationRepository;

class PopulationQueryHandlerTest extends TestCase
{
    public function testExecuteReturnsCorrectDto(): void
    {
        $populationRepositoryMock = $this->createMock(PopulationRepository::class);
        $populationData = [
            Population::create(1, 'Employees'),
            Population::create(2, 'External Contractors'),
        ];
        $populationRepositoryMock->expects($this->once())
            ->method('all')
            ->willReturn($populationData);

        $dtoAssemblerMock = $this->createMock(PopulationDtoAssembler::class);
        $expectedDtoResponse = [
            [
                'id' => 1,
                'name' => 'Employees',
                'fields' => [],
            ],
            [
                'id' => 2,
                'name' => 'External Contractors',
                'fields' => [],
            ],
        ];
        $dtoAssemblerMock->expects($this->once())
            ->method('assemble')
            ->with($populationData)
            ->willReturn($expectedDtoResponse);

        $handler = new PopulationQueryHandler($populationRepositoryMock, $dtoAssemblerMock);
        $result = $handler->execute();

        $this->assertEquals($expectedDtoResponse, $result);
    }
}
