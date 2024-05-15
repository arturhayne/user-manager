<?php

declare(strict_types=1);

namespace UserManager\Domain;

use Doctrine\Common\Collections\Collection;

class User
{
    private ?int $id;
    private int $populationId;
    private string $username;
    private string $password;
    private Population $population;

    private function __construct(int $populationId, string $username, string $password, ?int $id = null, ?Population $population = null)
    {
        $this->id = $id;
        $this->populationId = $populationId;
        $this->username = $username;
        $this->password = $password;
        $this->population = $population;
    }

    public static function create(int $populationId, string $username, string $password): self
    {
        return new self($populationId, $username, $password);
    }

    public static function createWithPopulation(int $populationId, string $username, string $password, $id, Population $population): self
    {
        return new self($populationId, $username, $password, $id, $population);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPopulationId(): int
    {
        return $this->populationId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPopulation(): Population
    {
        return $this->population;
    }

    public function getPopulationFields(): Collection
    {
        return $this->population->getPopulationFields();
    }
}
