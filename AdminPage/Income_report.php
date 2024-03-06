<?php
include "../db_config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $daily_query = "SELECT DATE(Order_date) as date, SUM(Order_total) as total_amount FROM OrderHeader WHERE Order_date BETWEEN '$start_date' AND '$end_date' GROUP BY DATE(Order_date)";
    $weekly_query = "SELECT YEAR(Order_date) AS year, WEEK(Order_date) AS week, SUM(Order_total) as total_amount FROM OrderHeader WHERE Order_date BETWEEN '$start_date' AND '$end_date' GROUP BY YEAR(Order_date), WEEK(Order_date)";
    $monthly_query = "SELECT YEAR(Order_date) AS year, MONTH(Order_date) AS month, SUM(Order_total) as total_amount FROM OrderHeader WHERE Order_date BETWEEN '$start_date' AND '$end_date' GROUP BY YEAR(Order_date), MONTH(Order_date)";
    $daily_result = mysqli_query($conn, $daily_query);
    $weekly_result = mysqli_query($conn, $weekly_query);
    $monthly_result = mysqli_query($conn, $monthly_query);
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Income Report</title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="Back_Styles/Adminpage.css">
</head>

<body>
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
            <h2>Income Summary Report</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required>
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" required>
                <input type="submit" value="Generate Report">
            </form>

            <?php if ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
                <h3>Daily Summary</h3>
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Total Amount</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($daily_result)) : ?>
                        <tr>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php echo $row['total_amount']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>

                <h3>Weekly Summary</h3>
                <table>
                    <tr>
                        <th>Year</th>
                        <th>Week</th>
                        <th>Total Amount</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($weekly_result)) : ?>
                        <tr>
                            <td><?php echo $row['year']; ?></td>
                            <td><?php echo $row['week']; ?></td>
                            <td><?php echo $row['total_amount']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>

                <h3>Monthly Summary</h3>
                <table>
                    <tr>
                        <th>Year</th>
                        <th>Month</th>
                        <th>Total Amount</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($monthly_result)) : ?>
                        <tr>
                            <td><?php echo $row['year']; ?></td>
                            <td><?php echo $row['month']; ?></td>
                            <td><?php echo $row['total_amount']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>
