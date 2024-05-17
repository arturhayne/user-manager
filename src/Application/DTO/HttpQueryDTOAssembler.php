<?php

declare(strict_types=1);

namespace UserManager\Application\DTO;

class HttpQueryDTOAssembler implements PopulationDtoAssembler
{
    public function assemble(array $population): mixed
    {
        return HttpPopulationQueryDtoAssembler::create($population);
    }
}
