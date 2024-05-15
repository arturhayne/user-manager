<?php

declare(strict_types=1);

namespace UserManager\Infrastructure;

interface UserValuesRepository
{
    public function isUnique(string $value, string $fieldName, int $populationId): bool;

    public function isUniqueAcrossPopulation(string $value, string $fieldName): bool;
}
