<?php

declare(strict_types=1);

namespace UserManager\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class User
{
    private ?int $id;
    private string $username;
    private string $password;
    private Population $population;
    private Collection $userValues;

    private function __construct(string $username, string $password, Population $population, ?int $id = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->population = $population;
        $this->userValues = new ArrayCollection();
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

    public function addUserValue(UserValue $userValue): self
    {
        if (!$this->userValues->contains($userValue)) {
            $this->userValues->add($userValue);
        }

        return $this;
    }

    public function getUserValues(): Collection
    {
        return $this->userValues;
    }

    public function getUserValueByFieldId(int $fieldId): ?UserValue
    {
        foreach ($this->userValues as $userValue) {
            if ($userValue->getFieldId() === $fieldId) {
                return $userValue;
            }
        }

        return null;
    }

    public function setUserValuesFromArray(array $values): void
    {
        $populationFields = $this->getPopulationFields()->toArray();

        if (empty($validationErrors)) {
            foreach ($populationFields as $populationField) {
                $fieldName = $populationField->getName();
                if (isset($values[$fieldName])) {
                    $userValue = new UserValue(
                        $this->id,
                        $populationField,
                        $values[$fieldName]
                    );
                    $this->addUserValue($userValue);
                }
            }
        }
    }
}
