<?php
session_start();

if (!isset($_SESSION["CustNo"])) {
    header("Location: login.html");
    exit();
}
include "db_config.php";

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $query = "SELECT * FROM OrderDetail WHERE OrderId = '$order_id'";
    $result = mysqli_query($conn, $query);
} else {
    header("Location: order_history.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/order_history.css">

    <title>Order Details</title>
</head>
<body>
<?php
    include "header.php";
?>

    <h1>Order Details</h1>
    <h2>Order ID: <?php echo $order_id; ?></h2>
    <table border="1">
        <tr>
            <th>Product Code</th>
            <th>Product Name</th>
            <th>Price Per Unit</th>
            <th>Quantity</th>
            <th>Line Total</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?php echo $row['ProductCode']; ?></td>
                <td><?php echo $row['ProductName']; ?></td>
                <td><?php echo $row['PricePerUnit']; ?></td>
                <td><?php echo $row['Qty']; ?></td>
                <td><?php echo $row['LineTotal']; ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <br>
    <a href="order_history.php">Back to Order History</a>
</body>
</html>

<?php
mysqli_close($conn);
?>
