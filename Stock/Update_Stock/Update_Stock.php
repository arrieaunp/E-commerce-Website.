<?php
    $a1 = $_GET['a1'];

    /* get connection */
    $conn = mysqli_connect("localhost", "root", "", "mydb");
    /* run query to get data based on a1 */
    $stmt = mysqli_query($conn, "SELECT * FROM Stock WHERE ProductCode = '$a1'");


while ($row = mysqli_fetch_array($stmt)) {
    $name1 = $row["ProductCode"];
    $name2 = $row["ProductName"];
    $name3 = $row["PricePerUnit"];
    $name4 = $row["StockQty"];
    $name5 = $row["AddNewField"];
}    
mysqli_close($conn);
?>

<html>
<form method="get" action="SaveDB_Stock.php">
    <h1>

        <input type="hidden" name="a1" value="<?php echo $name1 ?>">
        <?php echo $name1 ?>
        ชื่อสินค้า
        <input type="text" name="a2" size="20" maxlength="20" value="<?php echo $name2 ?>"><br>
        ราคาต่อหน่วย 
        <input type="text" name="a3" size="1" maxlength="1" value="<?php echo $name3 ?>"><br>
        รายการคงเหลือ 
        <input type="text" name="a4" size="50" maxlength="80" value="<?php echo $name4 ?>"><br>
        รายละเอียด 
        <input type="text" name="a5" size="10" maxlength="10" value="<?php echo $name5 ?>"><br>

        <input type="submit" value="ยืนยัน">
        <input type="reset" value="ยกเลิก">
    </h1>
</form>

</body>
</html>