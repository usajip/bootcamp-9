<?php
// php database connection with PDO
$host = 'localhost';
$db   = 'bootcamp_9';
$user = 'root';
$pass = 'new_password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected to the database successfully!";
} catch (\PDOException $e) {
    // echo "Connection failed: " . $e->getMessage();
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>