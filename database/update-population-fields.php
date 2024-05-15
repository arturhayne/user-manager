<?php

$dbFile = '/var/www/html/database.sqlite';
$dataFile = '/var/www/html/database/unique-across-population.sql';

try {
    $pdo = new PDO("sqlite:$dbFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = file_get_contents($dataFile);
    $pdo->exec($sql);
    echo "Added new field is_unique_across_population.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
