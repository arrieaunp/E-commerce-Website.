<?php
session_start();
if (!isset($_SESSION['order_id'])) {
    header("Location: Invoice_customer.php");
    exit();
}?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/Invoice.css">
    <title>ใบเสร็จ</title>
</head>

<body>
<header>
    <nav>
        <ul>
            <li><a href="Menu.php">หน้าแรก</a></li>
            <li><a href="Order_history.php">ประวัติการซื้อ</a></li>
            <li><a href="Cart.php">ตะกร้าสินค้า</a></li>
            <li><a href="logout.php">Log Out</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <center>
        <h1>Receipt</h1>
        <?php if(isset($_SESSION["order_id"])): ?>
            <p>รหัสการสั่งซื้อ: <?php echo $_SESSION["order_id"]; ?></p>
        <?php endif; ?>
        <?php if(isset($_SESSION["order_date"])): ?>
            <p>วันที่การสั่งซื้อ: <?php echo $_SESSION["order_date"]; ?></p>
        <?php endif; ?>
        <?php if(isset($_SESSION["custNo"])): ?>
            <p>รหัสลูกค้า: <?php echo $_SESSION["custNo"]; ?></p>
        <?php endif; ?>
        <?php if(isset($_SESSION["custName"])): ?>
            <p>ชื่อ: <?php echo $_SESSION["custName"]; ?></p>
        <?php endif; ?>
        <?php if(isset($_SESSION["address"])): ?>
            <p>ที่อยู่: <?php echo $_SESSION["address"]; ?></p>
        <?php endif; ?>

        <table>
            <tr>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>ราคาต่อหน่วย</th>
                <th>จำนวน</th>
                <th>ราคารวม</th>
            </tr>

            <?php
            if (isset($_SESSION["product_details"]) && is_array($_SESSION["product_details"])) {
                foreach ($_SESSION["product_details"] as $product) {
                    echo "<tr>";
                    echo "<td>" . $product["code"] . "</td>";
                    echo "<td>" . $product["name"] . "</td>";
                    echo "<td>" . $product["price"] . "</td>";
                    echo "<td>" . $product["quantity"] . "</td>";
                    echo "<td>" . ($product["price"] * $product["quantity"]) . "</td>";
                    echo "</tr>";
                }
            }
            ?>

            <tr class="total">
                <td colspan="4">ยอดรวมการสั่งซื้อ</td>
                <td><?php echo $_SESSION["order_total"] ?? ''; ?></td>
            </tr>
        </table>
        <center>
            <a href="Menu.php">
                <button onclick="clearSession()">Back to Order!</button>
            </a>
        </center>
        
    </center>
</div>
<form action="export.php" method="POST">
    <label for="export_format">Export Format:</label>
    <select name="export_format" id="export_format">
        <option value="pdf">PDF</option>
        <option value="excel">Excel</option>
    </select>
    <button type="submit">Export</button>
</form>

<script>
    function clearSession() {
        <?php
        foreach ($_SESSION as $key => $value) {
            if ($key !== 'custNo') {
                unset($_SESSION[$key]);
            }
        }
        ?>
    }
</script>

</body>
</html>
