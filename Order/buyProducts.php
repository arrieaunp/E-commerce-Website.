<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="buyProduct_styles.css">
    <title>Buy Product</title>
</head>
<body>

<?php
$cx = mysqli_connect("localhost", "root", "", "mydb");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['selectedProducts'])) {
    $selectedProducts = $_GET['selectedProducts'];
    echo '<form action="Invoice_Customer.php" method="get">';
    echo '<h1>ซื้อสินค้า</h1>';

    foreach ($selectedProducts as $productCode) {
        $selectQuery = "SELECT * FROM Stock WHERE ProductCode = '$productCode'";
        $result = mysqli_query($cx, $selectQuery);
        
        while ($row = mysqli_fetch_array($result)) {
            $productName = $row['ProductName'];
            echo '<label for="productCode">รหัสสินค้า:</label>';
            echo '<input type="text" id="productCode" name="productCode[]" value="' . $row['ProductCode'] . '" readonly>';

            echo '<label for="productName">ชื่อสินค้า:</label>';
            echo '<input type="text" id="productName" name="productName[]" value="' . $productName . '" readonly>';

            echo '<label for="quantity">จำนวนที่ต้องการซื้อ:</label>';
            echo '<input type="number" id="quantity" name="quantity[]" min="1" required>';
        }
    }
    echo '<label for="custNo">ไอดีลูกค้า (CustNo):</label>';
    echo '<input type="text" id="custNo" name="custNo" required>';

    echo '<button type="submit">ยืนยันการซื้อ</button>';
    
    echo '</form>';
}
?>


<?php
mysqli_close($cx);
?>
</body>
</html>
