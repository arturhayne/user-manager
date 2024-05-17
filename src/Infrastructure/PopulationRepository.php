<?php

declare(strict_types=1);

namespace UserManager\Infrastructure;

interface PopulationRepository
{
    public function all(): array;
}
