<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== true) {
    // Redirect to the login page if not logged in as admin
    header("Location: login.html");
    exit();
}

// Check if the order ID is provided in the URL
if (!isset($_GET['id'])) {
    echo "Order ID not provided.";
    exit();
}

// Include database connection
$conn = mysqli_connect("localhost", "root", "", "mydb");

// Get the order ID from the URL
$order_id = $_GET['id'];

// Delete order details from Order_detail table
$delete_detail_query = "DELETE FROM Order_detail WHERE Order_id = '$order_id'";
if (mysqli_query($conn, $delete_detail_query)) {
    // Order details deleted successfully
    // Now delete the order from Order_header table
    $delete_header_query = "DELETE FROM Order_header WHERE Order_id = '$order_id'";
    if (mysqli_query($conn, $delete_header_query)) {
        // Order deleted successfully
        echo "Order with ID $order_id deleted successfully.";
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
