<?php
session_start();

if (!isset($_SESSION["CustNo"])) {
    header("Location: login.html");
    exit();
}

$CustNo = $_SESSION["CustNo"];
$CustName = $_SESSION["CustName"];
$Address = $_SESSION["Address"];

$payment_method = $_POST["payment_method"];
$conn = mysqli_connect("localhost", "root", "", "mydb");
if ($payment_method == "pay_now") {
    $order_status = "Paid";
} else {
    $order_status = "Pending";
}
//date
$order_date = date("Y-m-d H:i:s");

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
$sql = "SELECT MAX(Order_id) AS max_id FROM OrderHeader";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if ($row["max_id"] == NULL) {
    $order_id = 1;
} else {
    $order_id = $row["max_id"] + 1;
}

// บันทึกข้อมูลการชำระเงินลงใน OrderHeader
$query = "INSERT INTO OrderHeader (Order_id, Order_date, CustNo, CustName, Address, Order_status, Order_total) 
          VALUES ('$order_id', '$order_date', '$CustNo', '$CustName', '$Address', '$order_status', $total)";
mysqli_query($conn, $query);

// บันทึกข้อมูลสินค้าในตะกร้าลงใน OrderDetail
foreach ($product_details as $product) {
    $ProductCode = $product["code"];
    $ProductName = $product["name"];
    $PricePerUnit = $product["price"];
    $Qty = $product["quantity"];
    $line_total = $PricePerUnit * $Qty;
    
    $query = "INSERT INTO OrderDetail (Order_id, ProductCode, ProductName, PricePerUnit, Qty, Line_total) 
              SELECT '$order_id', '$ProductCode', '$ProductName', $PricePerUnit, $Qty, $line_total
              FROM OrderHeader
              WHERE Order_id = $order_id";
    
    mysqli_query($conn, $query);
}

// Store order details in session
$_SESSION["order_id"] = $order_id;
$_SESSION["order_date"] = $order_date;
$_SESSION["custNo"] = $CustNo;
$_SESSION["custName"] = $CustName;
$_SESSION["address"] = $Address;
$_SESSION["order_total"] = $total;
$_SESSION["product_details"] = $product_details;

header("Location: Invoice_customer.php");
exit();
?>
