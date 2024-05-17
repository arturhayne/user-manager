<?php

declare(strict_types=1);

namespace UserManager\Infrastructure;

use UserManager\Domain\Population;
use UserManager\Domain\PopulationField;
use UserManager\Domain\User;

class PdoUserRepository implements UserRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user): int
    {
        $stmt = $this->connection->prepare('
            INSERT INTO users (population_id, username, password)
            VALUES (:population_id, :username, :password)
        ');

        $stmt->execute([
            ':population_id' => $user->getPopulationId(),
            ':username' => $user->getUsername(),
            ':password' => $user->getPassword(),
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function hasUser(string $username): bool
    {
        $stmt = $this->connection->prepare('
            SELECT count(1) as user FROM users WHERE username = :username
        ');
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();

        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (isset($userData['user'])) {
            return false;
        }

        return true;
    }

    public function find(int $userId): ?User
    {
        $stmt = $this->connection->prepare('SELECT u.*, p.* FROM users u JOIN populations p ON u.population_id = p.id WHERE u.id = :id');
        $stmt->bindParam(':id', $userId, \PDO::PARAM_INT);
        $stmt->execute();

        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($userData === false) {
            return null;
        }

        $population = Population::create(
            (int) $userData['population_id'],
            $userData['name']
        );

        $populationFieldsStmt = $this->connection->prepare('SELECT * FROM population_fields WHERE population_id = :population_id');
        $populationFieldsStmt->bindParam(':population_id', $userData['population_id'], \PDO::PARAM_INT);
        $populationFieldsStmt->execute();

        while ($populationFieldData = $populationFieldsStmt->fetch(\PDO::FETCH_ASSOC)) {
            $populationField = new PopulationField(
                (int) $populationFieldData['id'],
                $populationFieldData['type'],
                (bool) $populationFieldData['required'],
                (bool) $populationFieldData['isunique'],
                (bool) $populationFieldData['multi'],
                (bool) $populationFieldData['sensitive'],
                $populationFieldData['name'],
                $populationFieldData['dname'],
                (int) $populationFieldData['population_id'],
                (bool) $populationFieldData['is_unique_across_population'],
            );
            $population->addPopulationField($populationField);
        }

        return User::create(
            $userData['username'],
            $userData['password'],
            $population
        );
    }
}
