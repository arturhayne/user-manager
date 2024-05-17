<?php

declare(strict_types=1);

namespace UserManager\Application;

use UserManager\Application\DTO\PopulationDtoAssembler;
use UserManager\Infrastructure\PopulationRepository;

class PopulationQueryHandler
{
    public function __construct(
        private PopulationRepository $repository,
        private PopulationDtoAssembler $dtoAssembler,
    ) {
    }

    public function execute()
    {
        $populations = $this->repository->all();

        return $this->dtoAssembler->assemble($populations);
    }
}
