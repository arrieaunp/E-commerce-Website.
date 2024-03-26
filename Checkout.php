<?php
session_start();
include "db_config.php";
include "header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="Styles/Checkout.css">

  <title>Checkout</title>
</head>

<body>
  <center>
    <h1>Checkout</h1>

    <?php
    if (isset($_SESSION["CustNo"])) {
      echo "<h2>ที่อยู่สำหรับจัดส่ง</h2>",
          "<label for='CustName'><b>ชื่อ :</b></label>",
          "<input type='text' id='CustName' name='CustName' value='" . $_SESSION["CustName"] . "'><br>",
          "<label for='address'><b>ที่อยู่ :</b></label>",
          "<input type='text' id='address' name='address' value='" . $_SESSION["Address"] . "'><br>",
          "<label for='tel'><b>เบอร์โทร :</b></label>",
          "<input type='text' id='tel' name='tel' value='" . $_SESSION["Tel"] . "'>";
    }else if(isset($_SESSION['google_loggedin']) == TRUE) {
      echo "<h2>ที่อยู่สำหรับจัดส่ง</h2>",
          "<label for='CustName'><b>ชื่อ :</b></label>",
          "<input type='text' id='CustName' name='CustName' value='" . $_SESSION['google_name'] . "'><br>",
          "<label for='address'><b>ที่อยู่ :</b></label>",
          "<input type='text' id='address' name='address'><br>",
          "<label for='tel'><b>เบอร์โทร :</b></label>",
          "<input type='text' id='tel' name='tel'>";
  }
   else {
      echo "<p>กรุณาทำการ login ก่อนทำการ Checkout</p>";
      exit();
  }      
    if (!empty($_SESSION["cart"])) {
      echo "<h2>รายการสินค้าในตะกร้า</h2>";
      echo "<table>",
        "<tr>",
        "<th>รูปภาพ</th>",
        "<th>ชื่อสินค้า</th>",
        "<th>ราคาต่อหน่วย</th>",
        "<th>จำนวน</th>",
        "<th>ราคารวม</th>",
        "</tr>";

      $total = 0;

      foreach ($_SESSION["cart"] as $product) {
        $subtotal = $product["price"] * $product["quantity"];
        $total += $subtotal;

        echo "<tr>",
          "<td><img class='img' src='image/" . $product["image"] . "'></td>",
          "<td>" . $product["name"] . "</td>",
          "<td>" . $product["price"] . " บาท</td>",
          "<td>" . $product["quantity"] . "</td>",
          "<td>" . $subtotal . " บาท</td>",
          "</tr>";
      }

      echo "<tr>",
        "<td colspan='4' align='right'><b>ยอดรวมทั้งหมด</b></td>",
        "<td>" . $total . " บาท</td>",
        "</tr>",
        "</table>";
    } else {
      echo "<p>ไม่มีสินค้าในตะกร้า</p>";
    }
    ?>

    <form action="Payment.php" method="post">
    <button type="submit">Check out</button>
    </form>

  </center>
</body>
</html>