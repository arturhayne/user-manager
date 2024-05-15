<?php

$dbFile = '/var/www/html/database.sqlite';
$sqlFile = '/var/www/html/database/db.sql';

try {
    if (!file_exists($dbFile)) {
        touch($dbFile);
    }

    $pdo = new PDO("sqlite:$dbFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = file_get_contents($sqlFile);

    $pdo->exec($sql);

    echo "Tables created successfully.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
