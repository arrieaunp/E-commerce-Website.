<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="Styles/Checkout.css">

  <title>Checkout</title>
</head>

<body>
  <header>
    <nav>
      <ul>
        <li><a href="Menu.php">หน้าแรก</a></li>
        <li><a href="Order_history.php">ประวัติการซื้อ</a></li>
        <li><a href="Cart.php">ตะกร้าสินค้า</a></li>
        <li><a href="logout.php">Log Out</a></li>

      </ul>
    </nav>
  </header>

  <center>
    <h1>Checkout</h1>

    <?php
    session_start();

    if (isset($_SESSION["CustNo"])) {
      echo "<h2>ที่อยู่สำหรับจัดส่ง</h2>",
          "<label for='cust_name'><b>ชื่อ :</b></label>",
          "<input type='text' id='cust_name' name='cust_name' value='" . $_SESSION["CustName"] . "'><br>",
          "<label for='address'><b>ที่อยู่ :</b></label>",
          "<input type='text' id='address' name='address' value='" . $_SESSION["Address"] . "'><br>",
          "<label for='tel'><b>เบอร์โทร :</b></label>",
          "<input type='text' id='tel' name='tel' value='" . $_SESSION["Tel"] . "'>";
  } else {
      echo "<p>กรุณาทำการ login ก่อนทำการ Checkout</p>";
      exit();
  }
      
    ?>

    <?php
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
