<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="invoiceCust_styles.css">
    <title>ใบเสร็จ</title>
<body>
<?php
    $cx = mysqli_connect("localhost", "root", "", "mydb");
    $productCode = $_GET["productCode"]; 
    $productName = $_GET["productName"]; 
    $quantity = $_GET["quantity"]; 
    $custNo = $_GET["custNo"]; 

    // สร้าง Order id
    $sql = "SELECT MAX(Order_id) AS max_id FROM Order_header";
    $result = mysqli_query($cx, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row["max_id"] == NULL) {
        $order_id = 1;
    } else {
        $order_id = $row["max_id"] + 1;
    }
    $order_date = date("Y-m-d"); 

    // ดึงข้อมูลลูกค้า
    $sql = "SELECT CustName, Address FROM Customer WHERE CustNo = '$custNo'";
    $result = mysqli_query($cx, $sql);
    $row = mysqli_fetch_assoc($result);
    $custName = $row["CustName"];
    $address = $row["Address"]; 

    // คำนวณยอดรวมการสั่งซื้อ
    $order_total = 0; 
    for ($i = 0; $i < count($productCode); $i++) { 
        $sql = "SELECT PricePerUnit FROM Stock WHERE ProductCode = '$productCode[$i]'";
        $result = mysqli_query($cx, $sql);
        $row = mysqli_fetch_assoc($result);
        $pricePerUnit = $row["PricePerUnit"]; 
        $line_total = $pricePerUnit * $quantity[$i]; 
        $order_total += $line_total; 
    }
    // เพิ่มข้อมูลลง Order_header
    $sql = "INSERT INTO Order_header (Order_id, Order_date, CustNo, CustName, Address, Order_status, Order_total) VALUES ('$order_id', '$order_date', '$custNo', '$custName', '$address', 'Pending', '$order_total')";
    mysqli_query($cx, $sql);
    
    for ($i = 0; $i < count($productCode); $i++) { 
        $sql = "SELECT PricePerUnit FROM Stock WHERE ProductCode = '$productCode[$i]'";
        $result = mysqli_query($cx, $sql);
        $row = mysqli_fetch_assoc($result);
        $pricePerUnit = $row["PricePerUnit"]; 
        $line_total = $pricePerUnit * $quantity[$i]; 
        $sql = "INSERT INTO Order_detail (Order_id, ProductCode, ProductName, PricePerUnit, Qty, Line_total) VALUES ('$order_id', '$productCode[$i]', '$productName[$i]', '$pricePerUnit', '$quantity[$i]', '$line_total')";
        mysqli_query($cx, $sql);
    }
    mysqli_close($cx);
?>
    <div class="container">
        <center>
        <h1>Receipt</h1>
        <p>รหัสการสั่งซื้อ: <?php echo $order_id; ?></p>
        <p>วันที่การสั่งซื้อ: <?php echo $order_date; ?></p>
        <p>รหัสลูกค้า: <?php echo $custNo; ?></p>
        <p>ชื่อ: <?php echo $custName; ?></p>
        <p>ที่อยู่: <?php echo $address; ?></p>
        <table>
            <tr>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>ราคาต่อหน่วย</th>
                <th>จำนวน</th>
                <th>ราคารวม</th>
            </tr>
            <?php
            for ($i = 0; $i < count($productCode); $i++) {
                echo "<tr>";
                echo "<td>" . $productCode[$i] . "</td>";
                echo "<td>" . $productName[$i] . "</td>";
                echo "<td>" . $pricePerUnit . "</td>";
                echo "<td>" . $quantity[$i] . "</td>";
                echo "<td>" . ($pricePerUnit * $quantity[$i]) . "</td>";
                echo "</tr>";
            }
            ?>
            <tr class="total">
                <td colspan="4">ยอดรวมการสั่งซื้อ</td>
                <td><?php echo $order_total; ?></td>
            </tr>
        </table>
        <center>
        <a href="Order.php">
    <button>Back to Order!</button>
</a>  
        </center>
        
    </div>
</body>
</html>
