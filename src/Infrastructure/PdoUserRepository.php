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

        return User::createWithPopulation(
            (int) $userData['population_id'],
            $userData['username'],
            $userData['password'],
            (int) $userData['id'],
            $population
        );
    }
}
