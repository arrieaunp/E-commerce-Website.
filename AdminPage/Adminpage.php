<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../AdminPage/Back_Styles/Adminpage.css">
    <title>Admin Page</title>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="../Customer/Show_Customer.php">Edit Customer</a></li>
            <li><a href="../Stock/Show_Stock.php">Edit Stock</a></li>
            <li class="logout"><a href="../logout.php">Log Out</a></li>
        </ul>
    </nav>
</header>

<?php
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== true) {
    header("Location: login.html");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "mydb");
$query = "SELECT * FROM Order_header";
$result = mysqli_query($conn, $query);
?>

<div class="container">
    <h1></h1>
    <div class="table-container">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th></th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['Order_id']; ?></td>
                        <td><?php echo $row['Order_date']; ?></td>
                        <td><?php echo $row['CustName']; ?></td>
                        <td><?php echo $row['Address']; ?></td>
                        <td><?php echo $row['Order_status']; ?></td>
                        <td><?php echo $row['Order_total']; ?></td>
                        <td>
                            <a href='edit_order.php?id=<?php echo $row['Order_id']; ?>' class="btn btn-edit">Edit</a>
                            <a href='delete_order.php?id=<?php echo $row['Order_id']; ?>' class="btn btn-delete">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>
</div>

<?php mysqli_close($conn); ?>
</body>
</html>
