<?php
session_start();
if (!isset($_SESSION['OrderId'])) {
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
<?php
    include "header.php";
?>

<div class="container">
    <center>
        <h1>Receipt</h1>
        <?php if(isset($_SESSION["OrderId"])): ?>
            <p>รหัสการสั่งซื้อ: <?php echo $_SESSION["OrderId"]; ?></p>
        <?php endif; ?>
        <?php if(isset($_SESSION["OrderDate"])): ?>
            <p>วันที่การสั่งซื้อ: <?php echo $_SESSION["OrderDate"]; ?></p>
        <?php endif; ?>
        <?php if(isset($_SESSION["CustNo"])): ?>
            <p>รหัสลูกค้า: <?php echo $_SESSION["CustNo"]; ?></p>
        <?php endif; ?>
        <?php if(isset($_SESSION["CustName"])): ?>
            <p>ชื่อ: <?php echo $_SESSION["CustName"]; ?></p>
        <?php endif; ?>
        <?php if(isset($_SESSION["Address"])): ?>
            <p>ที่อยู่: <?php echo $_SESSION["Address"]; ?></p>
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
                <td><?php echo $_SESSION["OrderTotal"] ?? ''; ?></td>
            </tr>
        </table>
        <center>
            <a href="index.php">
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
    $order_related_keys = ['OrderId', 'OrderDate', 'CustName', 'Address', 'ProductDetails', 'OrderTotal'];
    foreach ($order_related_keys); {
        if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
        }
    }
    }
</script>

</body>
</html>
