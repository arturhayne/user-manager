<?php

declare(strict_types=1);

namespace UserManager\Application;

class UserCommand
{
    private int $populationId;
    private string $userName;
    private string $password;
    private array $fields;

    private function __construct(int $populationId, string $userName, string $password, array $fields)
    {
        $this->populationId = $populationId;
        $this->userName = $userName;
        $this->password = $password;
        $this->fields = $fields;
    }

    public static function create(string $populationId, array $data): self
    {
        if (!isset($populationId, $data['user_name'], $data['password'])) {
            throw new \InvalidArgumentException('Invalid data array provided.');
        }

        return new self(
            (int) $populationId,
            $data['user_name'],
            $data['password'],
            $data
        );
    }

    public function getPopulationId(): int
    {
        return $this->populationId;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
