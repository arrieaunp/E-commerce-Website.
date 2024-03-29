<?php
ob_start();
session_start();
include "header.php";
include "db_config.php";

$CustNo = null; 

if (isset($_SESSION["CustNo"]) && $_SESSION["CustNo"] !== '') {
    $CustNo = $_SESSION["CustNo"];
    $CustName = $_POST["CustName"];
    $Address = $_POST["Address"];
    $Tel = $_POST["Tel"];
} else {
    $CustName = $_POST["CustName"] ?? "";
    $Address = $_POST["Address"] ?? "";
    $Tel = $_POST["Tel"];
}

// Debugging statements to check the value of $CustNo
echo "CustNo value before query execution: " . $CustNo;

// Ensure CustNo is not an empty string
$CustNo = ($CustNo === '') ? null : $CustNo;


$payment_method = $_POST["payment_method"];
if ($payment_method == "pay_now") {
    $order_status = "Paid";
} else {
    $order_status = "Pending";
}
//date
$order_date = date('Y-m-d', strtotime($order_date)); // Format the date as YYYY-MM-DD

//total
$total = 0;

// Array to store product details
$product_details = array();

foreach ($_SESSION["cart"] as $product) {
    $total += $product["price"] * $product["quantity"];
    // Storing product details in array
    $product_details[] = $product;
    //Updating stock
    $ProductCode = $product["code"];
    $Qty = $product["quantity"];
    $query = "UPDATE Stock SET StockQty = StockQty - $Qty WHERE ProductCode = '$ProductCode'";
    mysqli_query($conn, $query);
}

//id
$sql = "SELECT MAX(OrderId) AS max_id FROM OrderHeader";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if ($row["max_id"] == NULL) {
    $order_id = 1;
} else {
    $order_id = $row["max_id"] + 1;
}

// บันทึกข้อมูลการชำระเงินลงใน OrderHeader
$query = "INSERT INTO OrderHeader (OrderId, OrderDate, CustNo, CustName, Address, OrderStatus, OrderTotal) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sssssss", $order_id, $order_date, $CustNo, $CustName, $Address, $order_status, $total);

// Execute the statement
if (!mysqli_stmt_execute($stmt)) {
    echo "Error executing query: " . mysqli_stmt_error($stmt);
}

// บันทึกข้อมูลสินค้าในตะกร้าลงใน OrderDetail
foreach ($product_details as $product) {
    $ProductCode = $product["code"];
    $ProductName = $product["name"];
    $PricePerUnit = $product["price"];
    $Qty = $product["quantity"];
    $line_total = $PricePerUnit * $Qty;
    
    $query = "INSERT INTO OrderDetail (OrderId, ProductCode, ProductName, PricePerUnit, Qty, LineTotal) 
              SELECT '$order_id', '$ProductCode', '$ProductName', $PricePerUnit, $Qty, $line_total
              FROM OrderHeader
              WHERE OrderId = $order_id";
    
    mysqli_query($conn, $query);
}
// Store order details in session
$_SESSION["OrderId"] = $order_id;
$_SESSION["OrderDate"] = $order_date;
$_SESSION["CustNo"] = $CustNo;
$_SESSION["CustName"] = $CustName;
$_SESSION["Address"] = $Address;
$_SESSION["Tel"] = $Tel;
$_SESSION["OrderTotal"] = $total;
$_SESSION["product_details"] = $product_details;
header("Location: Invoice_customer.php");
exit();
?>
