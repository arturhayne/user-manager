<?php

declare(strict_types=1);

namespace UserManager\Infrastructure;

use UserManager\Domain\Population;
use UserManager\Domain\PopulationField;

class SqlitePopulationRepository implements PopulationRepository
{
    private \SQLite3 $db;

    public function __construct(\SQLite3 $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        $query = $this->db->query('
            SELECT 
                p.id as population_id, 
                p.name as population_name, 
                pf.id as field_id, 
                pf.type, 
                pf.required, 
                pf.isunique, 
                pf.multi, 
                pf.sensitive, 
                pf.name as field_name, 
                pf.dname, 
                pf.population_id as field_population_id,
                pf.is_unique_across_population
            FROM 
                populations p 
            JOIN 
                population_fields pf ON p.id = pf.population_id
        ');

        $populations = [];
        $currentPopulation = null;

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            if ($currentPopulation === null || $currentPopulation->getId() !== (int) $row['population_id']) {
                $currentPopulation = Population::create((int) $row['population_id'], $row['population_name']);
                $populations[] = $currentPopulation;
            }

            $populationField = new PopulationField(
                (int) $row['field_id'],
                $row['type'],
                (bool) $row['required'],
                (bool) $row['isunique'],
                (bool) $row['multi'],
                (bool) $row['sensitive'],
                $row['field_name'],
                $row['dname'],
                (int) $row['field_population_id'],
                (bool) $row['is_unique_across_population'],
            );

            $currentPopulation->addPopulationField($populationField);
        }

        return $populations;
    }

    public function find(int $populationId): ?Population
    {
        $statement = $this->db->prepare('
            SELECT 
                p.id as population_id,
                p.name as population_name,
                pf.id as field_id,
                pf.type,
                pf.required,
                pf.isunique,
                pf.multi,
                pf.sensitive,
                pf.name as field_name,
                pf.dname,
                pf.population_id,
                pf.is_unique_across_population
            FROM 
                populations p
            LEFT JOIN 
                population_fields pf ON p.id = pf.population_id
            WHERE 
                p.id = :populationId
        ');

        $statement->bindValue(':populationId', $populationId, SQLITE3_INTEGER);

        $result = $statement->execute();

        $population = null;
        $fields = [];

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            if ($population === null) {
                $population = Population::create($row['population_id'], $row['population_name']);
            }

            if ($row['field_id'] !== null) {
                $populationField = new PopulationField(
                    (int) $row['field_id'],
                    $row['type'],
                    (bool) $row['required'],
                    (bool) $row['isunique'],
                    (bool) $row['multi'],
                    (bool) $row['sensitive'],
                    $row['field_name'],
                    $row['dname'],
                    (int) $row['population_id'],
                    (bool) $row['is_unique_across_population']
                );
                $fields[] = $populationField;
            }
        }

        if ($population !== null) {
            foreach ($fields as $field) {
                $population->addPopulationField($field);
            }
        }

        return $population;
    }
}
