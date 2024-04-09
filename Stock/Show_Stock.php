<?php
require_once '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

if (isset($_COOKIE['token'])) {
    $jwt = $_COOKIE['token'];
    $secret_key = $_ENV['SECRETKEY'];

    try {
        $decoded = JWT::decode($_COOKIE['token'], new Key($secret_key, 'HS256'));
        if ($decoded->data->Role == "admin" || $decoded->data->Role == "superadmin") {
            
        } else {
            echo "Error: You don't have permission to access this page.";
            //header("Location: ../login.html");
            exit();
        }
    } catch (Exception $e) {
        "Error: " . $e->getMessage();
        //header("Location: ../login.html");
        exit();
    }
} else {
    echo "Error: Token not found.";
    //header("Location: ../login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Stock Reports</title>
</head>
<body>

    <header>
        <h1>Products</h1>
    </header>
    <center><span id="datetime"></span></center>
<script>
      function updateDateTime() {
        const now = new Date();
        const currentDateTime = now.toLocaleString();
        document.querySelector('#datetime').textContent = currentDateTime;
      }
      setInterval(updateDateTime, 1000);
</script>

<form action="" method="post">
        <label for="searchInput">ค้นหาสินค้า:</label>
        <input type="text" id="searchInput" name="searchInput">
        <button type="submit" class="searchButton">
</form>

<?php
include "../db_config.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchInput = mysqli_real_escape_string($conn, $_POST["searchInput"]);
        $searchQuery = "SELECT * FROM Stock WHERE ProductCode LIKE '%$searchInput%' OR ProductName LIKE '%$searchInput%'";
        $cur = mysqli_query($conn, $searchQuery);
    } else {
        $cur = mysqli_query($conn, "SELECT * FROM Stock WHERE 1");
    }
?>

<a href="/Fullstack/Stock/Insert_Stock/Insert_Stock.html" target="_blank">
    <button class="button button1">Insert Product</button>
</a>
<form action="Delete_Stock/Delete_Stock.php" method="post">
    <button type="submit" name="deleteSelected" class="button button1">Delete Selected</button>

<table>
    <tr>
        <th ></th>
        <th>ProductCode</th>
        <th>ProductName</th>
        <th>PricePerUnit</th>
        <th>StockQty</th>
        <th>Update</th>
        <th>Delete</th>
    </tr>

<?php
while ($row = mysqli_fetch_array($cur)) {
    echo '<tr>
            <td class="checkboxColumn"><input type="checkbox" name="selectedProducts[]" value="' . $row['ProductCode'] . '"></td>
            <td>' . $row['ProductCode'] . '</td>
            <td>' . $row['ProductName'] . '</td>
            <td>' . $row['PricePerUnit'] . '</td>
            <td>' . $row['StockQty'] . '</td>
            <td><a href="/Fullstack/Stock/Update_Stock/Update_Stock.php?a1=' . $row['ProductCode'] . '">Update</a></td>
            <td><a href="/Fullstack/Stock/Delete_Stock/Delete_Stock.php?a1=' . $row['ProductCode'] . '">Delete</a></td>
          </tr>';
}
    mysqli_close($conn);
?>

</table>
    
