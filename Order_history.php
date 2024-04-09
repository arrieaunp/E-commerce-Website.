<?php
include "header.php";
include "db_config.php";
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$secret_key = $_ENV['SECRETKEY'];

if (!isset($_COOKIE['token'])) {
  header('location:index.php');
  exit();
}

try {
    $decoded = JWT::decode($_COOKIE['token'], new Key($secret_key, 'HS256'));
    $CustNo = $decoded->data->UserId;
  } catch (Exception $e) {
    header('location:login.html');
    exit();
  }

$stmt = mysqli_prepare($conn, "SELECT * FROM OrderHeader WHERE CustNo = ? ORDER BY OrderDate DESC");
mysqli_stmt_bind_param($stmt, "s", $CustNo);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
  
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
        if (mysqli_num_rows($result) > 0) {
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
        }
        ?>
    </table>
</body>
</html>

<?php
mysqli_close($conn);
?>
