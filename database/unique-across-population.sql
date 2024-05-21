ALTER TABLE population_fields ADD COLUMN is_unique_across_population BOOLEAN NOT NULL DEFAULT FALSE;

UPDATE population_fields SET is_unique_across_population = 1 WHERE name IN ('email', 'employeeid') and population_id in (1,2);