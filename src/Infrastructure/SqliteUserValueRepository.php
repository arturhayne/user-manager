<?php

declare(strict_types=1);

namespace UserManager\Infrastructure;

class SqliteUserValueRepository implements UserValuesRepository
{
    private \SQLite3 $db;

    public function __construct(\SQLite3 $db)
    {
        $this->db = $db;
    }

    public function isUnique(string $value, string $fieldName, int $populationId): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM user_values 
                                    JOIN population_fields ON user_values.field_id = population_fields.id
                                    WHERE value = :value AND name = :fieldName AND population_id = :populationId');
        $stmt->bindValue(':value', $value, SQLITE3_TEXT);
        $stmt->bindValue(':fieldName', $fieldName, SQLITE3_TEXT);
        $stmt->bindValue(':populationId', $populationId, SQLITE3_INTEGER);

        $result = $stmt->execute();
        $count = (int) $result->fetchArray(SQLITE3_NUM)[0];

        return $count === 0;
    }

    public function isUniqueAcrossPopulation(string $value, string $fieldName): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM user_values 
                                    JOIN population_fields ON user_values.field_id = population_fields.id
                                    WHERE value = :value AND name = :fieldName');
        $stmt->bindValue(':value', $value, SQLITE3_TEXT);
        $stmt->bindValue(':fieldName', $fieldName, SQLITE3_TEXT);

        $result = $stmt->execute();
        $count = (int) $result->fetchArray(SQLITE3_NUM)[0];

        return $count === 0;
    }
}
