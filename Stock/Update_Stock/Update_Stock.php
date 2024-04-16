<?php
include "db_config.php";

    $a1 = $_GET['a1'];

    /* run query to get data based on a1 */
    $stmt = mysqli_query($conn, "SELECT * FROM Stock WHERE ProductCode = '$a1'");


while ($row = mysqli_fetch_array($stmt)) {
    $name1 = $row["ProductCode"];
    $name2 = $row["ProductName"];
    $name3 = $row["PricePerUnit"];
    $name4 = $row["StockQty"];
}    
mysqli_close($conn);
?>

<html>
<link rel="stylesheet" href="Updatepage.css">
<form method="get" action="SaveDB_Stock.php">
<div class="container">

        <input type="hidden" name="a1" value="<?php echo $name1 ?>">
        <?php echo $name1 ?>
        <br>
        <label for="a2">ชื่อสินค้า:</label>
        <input type="text" name="a2" size="20" maxlength="20" value="<?php echo $name2 ?>"><br>
        <label for="a3">ราคาต่อหน่วย:</label>
        <input type="text" name="a3" size="10" maxlength="1" value="<?php echo $name3 ?>"><br>
        รายการคงเหลือ : <?php echo $name4 ?>
<br>
        <label for="a4">เพิ่มสินค้า:</label>
        <input type="int" name="a4" id='increseQty' size="50" maxlength="80"><br>
        <label for="a4">ลดจำนวนสินค้า:</label>
        <input type="int" name="a5" id='decreaseQty' size="50" maxlength="80"><br>
<br>
        <input type="submit" value="ยืนยัน">
        <input type="reset" value="ยกเลิก">
</form>
</div>

</body>
</html>