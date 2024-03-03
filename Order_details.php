<?php
session_start();

if (!isset($_SESSION["CustNo"])) {
    header("Location: login.html");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "mydb");

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $query = "SELECT * FROM Order_detail WHERE Order_id = '$order_id'";
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
    <title>Order Details</title>
</head>
<body>
<header>
        <nav>
            <ul>
                <li><a href="Menu.php">หน้าแรก</a></li>
                <li><a href="Order_history.php">ประวัติการซื้อ</a></li>
                <li><a href="Cart.php">ตะกร้าสินค้า</a></li>
            </ul>
        </nav>
    </header>

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
                <td><?php echo $row['Line_total']; ?></td>
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
