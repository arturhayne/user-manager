<?php

declare(strict_types=1);

namespace UserManager\Domain;

class PopulationField
{
    private int $id;
    private string $type;
    private bool $required;
    private bool $isUnique;
    private bool $multi;
    private bool $sensitive;
    private string $name;
    private string $displayName;
    private int $populationId;
    private bool $isUniqueAcrossPopulation;

    public function __construct(
        int $id,
        string $type,
        bool $required,
        bool $isUnique,
        bool $multi,
        bool $sensitive,
        string $name,
        string $displayName,
        int $populationId,
        bool $isUniqueAcrossPopulation,
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->required = $required;
        $this->isUnique = $isUnique;
        $this->multi = $multi;
        $this->sensitive = $sensitive;
        $this->name = $name;
        $this->displayName = $displayName;
        $this->populationId = $populationId;
        $this->isUniqueAcrossPopulation = $isUniqueAcrossPopulation;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isUnique(): bool
    {
        return $this->isUnique;
    }

    public function isMulti(): bool
    {
        return $this->multi;
    }

    public function isSensitive(): bool
    {
        return $this->sensitive;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function getPopulationId(): int
    {
        return $this->populationId;
    }

    public function isUniqueAcrossPopulation(): bool
    {
        return $this->isUniqueAcrossPopulation;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType(),
            'required' => $this->isRequired(),
            'isUnique' => $this->isUnique(),
            'multi' => $this->isMulti(),
            'sensitive' => $this->isSensitive(),
            'name' => $this->getName(),
            'displayName' => $this->getDisplayName(),
            'populationId' => $this->getPopulationId(),
            'isUniqueAcrossPopulation' => $this->isUniqueAcrossPopulation(),
        ];
    }
}
