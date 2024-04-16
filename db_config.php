<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$serverName = $_ENV['HOST'];
$databaseName = $_ENV['DATABASE'];
$dbUsername = $_ENV['USER'];
$dbPassword = $_ENV['PASSWORD'];

$conn = new mysqli($serverName, $dbUsername, $dbPassword, $databaseName,);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
