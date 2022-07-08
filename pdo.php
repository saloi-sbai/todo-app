<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "todo_app";

$dsn = "mysql:host=$dbHost;dbname=$dbName";

try {
    $pdo = new PDO(
        $dsn,
        $dbUser,
        $dbPassword
    );
} catch (PDOException $e) {
    echo "Message d'erreur: " . $e->getMessage();
}
