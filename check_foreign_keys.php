<?php

// Load Laravel's database configuration
require_once 'vendor/autoload.php';

// Get database configuration from Laravel
$config = require 'config/database.php';
$connection = $config['connections']['mysql'];

// Create PDO connection
$dsn = "mysql:host={$connection['host']};dbname={$connection['database']};charset={$connection['charset']}";
$pdo = new PDO($dsn, $connection['username'], $connection['password']);

// Query to get foreign key constraints for the transactions table
$sql = "SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME,
    UPDATE_RULE,
    DELETE_RULE
FROM information_schema.KEY_COLUMN_USAGE kcu
JOIN information_schema.REFERENTIAL_CONSTRAINTS rc 
    ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME 
    AND kcu.TABLE_SCHEMA = rc.CONSTRAINT_SCHEMA
WHERE kcu.TABLE_SCHEMA = :database 
    AND kcu.TABLE_NAME = 'transactions' 
    AND kcu.COLUMN_NAME = 'demand_id'";

$stmt = $pdo->prepare($sql);
$stmt->execute(['database' => $connection['database']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    echo "Foreign key constraint found:\n";
    print_r($result);
} else {
    echo "No foreign key constraint found for demand_id in transactions table.\n";
}