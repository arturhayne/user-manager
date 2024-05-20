<?php

declare(strict_types=1);

namespace UserManager\Domain;

class UserValue
{
    private ?int $userId;
    private PopulationField $field;
    private string $value;

    public function __construct(?int $userId, PopulationField $field, string $value)
    {
        $this->userId = $userId;
        $this->field = $field;
        $this->value = $value;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getField(): PopulationField
    {
        return $this->field;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
