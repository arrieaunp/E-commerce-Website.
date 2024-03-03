<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Order_styles.css">
    <title>Order</title>
</head>
<body>

    <header>
        <h1>รายการสินค้า</h1>
    </header>

<?php
    session_start();
    $_SESSION['ktp'] = 'KTP test';
?>

<?php
    $cx = mysqli_connect("localhost", "root", "", "mydb");
    $cur = mysqli_query($cx, "SELECT * FROM `Stock` WHERE 1");
?>

<table>
    <tr>
        <th></th>
        <th>รหัสสินค้า</th>
        <th>ชื่อสินค้า</th>
        <th>ราคาต่อหน่วย</th>
        <th>จำนวนคงเหลือ</th>
    </tr>

<form method="GET" action="buyProducts.php">
<?php
while ($row = mysqli_fetch_array($cur)) {
    echo '<tr>
            <td class="checkboxColumn"><input type="checkbox" name="selectedProducts[]" value="' . $row['ProductCode'] . '"></td>
            <td>' . $row['ProductCode'] . '</td>
            <td>' . $row['ProductName'] . '</td>
            <td>' . $row['PricePerUnit'] . '</td>
            <td>' . $row['StockQty'] . '</td>
          </tr>';
}
    mysqli_close($cx);
?>
</table>
<center>
    <a href="http://localhost/bb/Order/buyProducts.php" target="_blank">
    <button class="button button1"> Buy! </button>
</a>

</center>
