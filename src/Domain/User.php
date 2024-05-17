<?php

declare(strict_types=1);

namespace UserManager\Domain;

use Doctrine\Common\Collections\Collection;

class User
{
    private ?int $id;
    private string $username;
    private string $password;
    private Population $population;

    private function __construct(string $username, string $password, Population $population, ?int $id = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->population = $population;
    }

    public static function create(string $username, string $password, Population $population): self
    {
        return new self($username, $password, $population);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPopulationId(): int
    {
        return $this->population->getId();
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
