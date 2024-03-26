<?php
session_start();
include "header.php";
include "db_config.php";

if (!isset($_SESSION["CustNo"])) {
    header("Location: login.html");
    exit();
}

$CustNo = $_SESSION["CustNo"];
$query = "SELECT * FROM OrderHeader WHERE CustNo = '$CustNo' ORDER BY OrderDate DESC";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/order_history.css">
    <title>Order History</title>
</head>
<body>
    <h1>Order History</h1>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th></th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $order_id = $row['OrderId'];
            $order_date = $row['OrderDate'];
            $order_total = $row['OrderTotal'];
            $order_status = $row['OrderStatus'];
            ?>
            <tr>
                <td><?php echo $order_id; ?></td>
                <td><?php echo $order_date; ?></td>
                <td><?php echo $order_total; ?></td>
                <td><?php echo $order_status; ?></td>
                <td><a href="Order_details.php?order_id=<?php echo $order_id; ?>">View Details</a></td>
            </tr>
            <?php
        }
        ?>
    </table>
</body>
</html>

<?php
mysqli_close($conn);
?>
