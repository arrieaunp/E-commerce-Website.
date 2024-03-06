<?php 
$a1 = $_GET['a1'];
$a2 = $_GET['a2'];
$a3 = $_GET['a3'];
$a4 = isset($_GET['a4']) ? intval($_GET['a4']) : 0; // หากไม่ได้รับค่าจากฟอร์มให้กำหนดเป็น 0
$a5 = isset($_GET['a5']) ? intval($_GET['a5']) : 0; // หากไม่ได้รับค่าจากฟอร์มให้กำหนดเป็น 0

/* get connection */
$conn=mysqli_connect("localhost","root","","mydb");

// Get the original StockQty from the database
$stmt = mysqli_prepare($conn, "SELECT StockQty FROM Stock WHERE ProductCode = ?");
mysqli_stmt_bind_param($stmt, "s", $a1);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $originalStockQty);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Calculate the new StockQty based on the adjustment
$newStockQty = $originalStockQty + $a4 - $a5; // Add or subtract the adjustment value from the original StockQty

/* run update */
$stmt = mysqli_prepare($conn, "UPDATE Stock SET ProductName = ?, PricePerUnit = ?, StockQty = ? WHERE ProductCode=?");
mysqli_stmt_bind_param($stmt, "ssss", $a2, $a3, $newStockQty, $a1);

/* check for errors */
if(!mysqli_stmt_execute($stmt)) {
    /* error */
    echo "Error";
} else {
    echo "Update data = <font color = red>'$a1'</font> is Successful";
}

/* close connection */
mysqli_close($conn);
header("Location: ../Show_Stock.php");

?>
