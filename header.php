<?php
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$decoded = null; // Initialize $decoded variable

if (isset($_COOKIE['token'])) {
    $jwt = $_COOKIE['token'];

    $secret_key = $_ENV['SECRETKEY'];

    try {
        $decoded = JWT::decode($_COOKIE['token'], new Key($secret_key, 'HS256'));
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        // header("Location: login.html");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Bar</title>
    <link rel="stylesheet" href="Styles/header.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="index.php">Lazadee Shoppa</a>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="index.php">หน้าแรก</a></li>
                    <li><a href="Order_history.php">ประวัติการซื้อ</a></li>
                    <li><a href="Cart.php">ตะกร้าสินค้า</a></li>
                </ul>
            </nav>
            <div class="account">
                <?php 
                if ($decoded && isset($decoded->data->Role) && ($decoded->data->Role == "admin" || $decoded->data->Role == "superadmin")) {
                    echo "<a href='AdminPage/Adminpage.php'><button class='button'>Admin Page</button></a>";
                } 
                ?>
                <?php include 'account_info.php'; ?>
            </div>
        </div>
    </header>
</body>
</html>
