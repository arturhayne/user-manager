<?php

$dbFile = '/var/www/html/database.sqlite';
$dataFile = '/var/www/html/database/data.sql';

try {
    $pdo = new PDO("sqlite:$dbFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = file_get_contents($dataFile);
    $pdo->exec($sql);
    echo "Data seeded successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
