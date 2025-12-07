<?php
// db_config.php
$host = 'localhost';
$dbname = 'nextlvl_games'; // Your database name
$username = 'root'; // Default username for XAMPP
$password = ''; // Default password for XAMPP

try {
    // Create PDO instance to connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    die("Database connection failed: " . $e->getMessage());
}
?>
