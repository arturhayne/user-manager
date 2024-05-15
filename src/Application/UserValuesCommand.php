<?php

declare(strict_types=1);

namespace UserManager\Application;

class UserValuesCommand
{
    private int $userId;
    private array $fields;

    private function __construct(int $userId, array $fields)
    {
        $this->userId = $userId;
        $this->fields = $fields;
    }

    public static function createFromArray(int $userId, array $data): self
    {
        if (!isset($data)) {
            throw new \InvalidArgumentException('Invalid data array provided.');
        }

        return new self(
            $userId,
            $data
        );
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
