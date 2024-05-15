<?php

declare(strict_types=1);

namespace UserManager\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Population
{
    private int $id;
    private string $name;
    private Collection $populationFields;

    private function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->populationFields = new ArrayCollection();
    }

    public static function create($id, $name): self
    {
        return new self($id, $name);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPopulationFields(): Collection
    {
        return $this->populationFields;
    }

    public function addPopulationField(PopulationField $populationField): self
    {
        if (!$this->populationFields->contains($populationField)) {
            $this->populationFields->add($populationField);
        }

        return $this;
    }
}
