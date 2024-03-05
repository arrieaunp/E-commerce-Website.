<?php
include "../db_config.php";
$query = "SELECT * FROM OrderHeader ORDER BY Order_date DESC";
$result = mysqli_query($conn, $query);

?>
<!doctype html>
<html lang="en">

<head>
  <title>Hello, world!</title>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <link rel="stylesheet" href="Back_Styles/adminpage.css" />

</head>

<header role="banner">
  <h1>Admin Panel</h1>
  <ul class="utilities">
    <br>
    <li class="users"><a href="#">My Account</a></li>
    <li class="logout warn"><a href="../logout.php">Log Out</a></li>
  </ul>
</header>

<nav role='navigation'>
  <ul class="main">
    <li class="dashboard"><a href="Adminpage.php">Dashboard</a></li>
    <li class="users"><a href="../Customer/Show_Customer.php">Customer</a></li>
    <li class="write"><a href="../Stock/Show_Stock.php">Stock</a></li>
    <li class="comments"><a href="report.php">Report</a></li>
  </ul>
</nav>

<main role="main">
  
  <section class="panel important">
    <h2>Order Recents</h2>
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

  </section>
  
</main>
