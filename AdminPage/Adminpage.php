<?php
include "../db_config.php";
$query = "SELECT * FROM OrderHeader ORDER BY Order_id DESC";
$result = mysqli_query($conn, $query);

?>
<!doctype html>
<html lang="en">

<head>
  <title>Admin Panel</title>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <link rel="stylesheet" href="Back_Styles/Adminpage.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
</head>

<header role="banner">
  <h1>Admin Panel</h1>
  <ul class="utilities">
    <br>
    <li class="users"><a href="#">My Account</a></li>
    <li class="logout warn"><a href="../logout.php">Log Out</a></li>
  </ul>
</header>

<?php include "sidenav.php"; ?>

<main role="main">  
  <section class="panel important">
            <h2>Income Summary</h2>
            <canvas id="incomeChart" width="800" height="400"></canvas>
        </section>

  
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
<script>
        <?php
        include "../db_config.php";
        $query = "SELECT DATE(Order_date) as order_date, SUM(Order_total) as total_amount FROM OrderHeader GROUP BY DATE(Order_date)";
        $result = mysqli_query($conn, $query);
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[$row['order_date']] = $row['total_amount'];
        }
        ?>

        const labels = <?php echo json_encode(array_keys($data)); ?>;
        const data = {
            labels: labels,
            datasets: [{
                label: 'Income per Day',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: <?php echo json_encode(array_values($data)); ?>,
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {}
        };

        var myChart = new Chart(
            document.getElementById('incomeChart'),
            config
        );
    </script>
</html>
