<?php

declare(strict_types=1);

namespace UserManager\Application\DTO;

interface PopulationDtoAssembler
{
    public function assemble(array $populations): mixed;
}
