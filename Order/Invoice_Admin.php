<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="invoiceAdmin_styles.css">
    <title>Stock Reports</title>
</head>
<body>

    <header>
        <h1>Invoice detail</h1>
    </header>

<?php
    $cx = mysqli_connect("localhost", "root", "", "mydb");
    $cur = mysqli_query($cx, "SELECT * FROM Order_detail ORDER BY Order_id");
?>

<table>
    <tr>
        <th>Order_id</th>
        <th>ProductCode</th>
        <th>ProductName</th>
        <th>PricePerUnit</th>
        <th>Qty</th>
        <th>Line_total</th>
    </tr>

<?php
$currentOrderId = null;

while ($row = mysqli_fetch_array($cur)) {
    echo '<tr>';
    
    if ($row['Order_id'] != $currentOrderId) {
        echo '<td>' . $row['Order_id'] . '</td>';
        $currentOrderId = $row['Order_id'];
    } else {
        echo '<td></td>';
    }

    echo '<td>' . $row['ProductCode'] . '</td>
            <td>' . $row['ProductName'] . '</td>
            <td>' . $row['PricePerUnit'] . '</td>
            <td>' . $row['Qty'] . '</td>
            <td>' . $row['Line_total'] . '</td>
          </tr>';
}
    mysqli_close($cx);
?>

</table>
    
</body>
</html>
