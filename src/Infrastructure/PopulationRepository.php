<?php

declare(strict_types=1);

namespace UserManager\Infrastructure;

use UserManager\Domain\Population;

interface PopulationRepository
{
    public function all(): array;

    public function find(int $populationId): ?Population;
}
