<?php
include "../db_config.php";
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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

// Check if the order ID is provided in the URL
if (!isset($_GET['id'])) {
    echo "Order ID not provided.";
    exit();
}

$OrderId = $_GET['id'];

// Delete order details from Order_detail table
$delete_detail_query = "DELETE FROM OrderDetail WHERE OrderId = '$OrderId'";
if (mysqli_query($conn, $delete_detail_query)) {
    // Order details deleted successfully
    // Now delete the order from Order_header table
    $delete_header_query = "DELETE FROM OrderHeader WHERE OrderId = '$OrderId'";
    if (mysqli_query($conn, $delete_header_query)) {
        // Order deleted successfully
        echo "Order with ID $OrderId deleted successfully.";
    } else {
        echo "Error deleting order from Order_header table: " . mysqli_error($conn);
    }
} else {
    echo "Error deleting order details from Order_detail table: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Order</title>
</head>
<body>
    <button onclick="location.href='Adminpage.php'">Back to Admin Page</button>
</body>
</html>
