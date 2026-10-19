<?php
require 'includes/config.php';

$sql = file_get_contents('db_schema.sql');

try {
    $pdo->exec($sql);
    echo "Database and tables created successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
