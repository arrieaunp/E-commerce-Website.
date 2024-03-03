<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "mydb");

if (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== true) {
    header("Location: login.html");
    exit();
}

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
} else {
    header("Location: Adminpage.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        $order_date = mysqli_real_escape_string($conn, $_POST['order_date']);
        $cust_name = mysqli_real_escape_string($conn, $_POST['cust_name']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        if (isset($_POST['status'])) {
            $status = mysqli_real_escape_string($conn, $_POST['status']);
        } else {
            $status = ''; 
        }

        $query = "UPDATE Order_header SET Order_date='$order_date', CustName='$cust_name', Address='$address', Order_status='$status' WHERE Order_id='$order_id'";
        $result = mysqli_query($conn, $query);
        
        if ($result) {
            header("Location: Adminpage.php");
            exit();
        } else {
            $error_message = "Failed to update order.";
        }
    } elseif (isset($_POST['delete'])) {
        $query = "DELETE FROM Order_header WHERE Order_id='$order_id'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            header("Location: Adminpage.php");
            exit();
        } else {
            $error_message = "Failed to delete order.";
        }
    }
}

$query = "SELECT * FROM Order_header WHERE Order_id = '$order_id'";
$result = mysqli_query($conn, $query);

if ($result) {
    $order = mysqli_fetch_assoc($result);
} else {
    header("Location: Adminpage.php");
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <link rel="stylesheet" href="../AdminPage/Back_Styles/edit.css">
</head>
<body>
    <div class="container">
        <h1>Edit Order</h1>
        <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=$order_id"; ?>" method="post">
            <input type="hidden" name="order_id" value="<?php echo $order['Order_id']; ?>">
            <label for="order_date">Order Date:</label>
            <input type="text" id="order_date" name="order_date" value="<?php echo $order['Order_date']; ?>">
            <label for="cust_name">Customer Name:</label>
            <input type="text" id="cust_name" name="cust_name" value="<?php echo $order['CustName']; ?>">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo $order['Address']; ?>">

            <label>Status:</label><br>
            <input type="radio" name="status" value="Paid" <?php if ($order['Order_status'] == 'Paid') echo 'checked="checked"'; ?>> Paid<br>
            <input type="radio" name="status" value="Pending" <?php if ($order['Order_status'] == 'Pending') echo 'checked="checked"'; ?>> Pending<br>
            
            <button type="submit" name="update" class="btn">Update</button>
            <button type="submit" name="delete" class="btn delete" onclick="return confirm('Are you sure you want to delete this order?')">Delete</button>
        </form>
    </div>
</body>
</html>
