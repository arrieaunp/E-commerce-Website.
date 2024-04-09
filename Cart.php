<?php
session_start();
include "db_config.php";

// require_once 'vendor/autoload.php';

// use Firebase\JWT\JWT;

// $secret_key = $_ENV['SECRETKEY'];

// $payload = array(
//   'iat' => time(),
//   'exp' => strtotime("+1 hour"),
//   'data' => array(
//     'code' => $row['ProductCode'],
//     'name' => $row['ProductName'],
//     'price' => $row['PricePerUnit'],
//     'image' => $row['ProductImg'],
//   ),
// );

// $jwt = JWT::encode($payload, $secret_key, 'HS256');
// setcookie("cart_token", $jwt, time() + 3600, "/", "", true, true);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="Styles/Cart.css">
  <form action="Checkout.php" method="post">
  <title>Cart</title>
</head>

<body>
  <?php
    include "header.php";
  ?>

  <div class="cart-container">
    <h1>Shopping Cart</h1>
    <?php
    $cart = array();
    if (isset($_GET["selected"])) {
      $ProductCode = $_GET["selected"];
      $cur = mysqli_query($conn, "SELECT * FROM `Stock` WHERE `ProductCode` = '$ProductCode'");
      if (mysqli_num_rows($cur) > 0) {
        $row = mysqli_fetch_array($cur);
        $product = array(
          "code" => $row["ProductCode"],
          "name" => $row["ProductName"],
          "price" => $row["PricePerUnit"],
          "image" => $row["ProductImg"],
          "quantity" => 1
        );
        if (isset($_SESSION["cart"][$ProductCode])) {
          $_SESSION["cart"][$ProductCode]["quantity"]++;
        } else {
          $_SESSION["cart"][$ProductCode] = $product;
        }
      }
    }

    if (isset($_GET["action"])) {
      $action = $_GET["action"];
      $code = $_GET["code"];
      if ($action == "remove") {
        unset($_SESSION["cart"][$code]);
      }
      if ($action == "update") {
        $quantity = $_GET["quantity"];
        $_SESSION["cart"][$code]["quantity"] = $quantity;
      }
    }

    if (!empty($_SESSION["cart"])) {
      echo "<table class='cart-table'>";
      echo "<tr><th>รูปภาพ</th><th>ชื่อสินค้า</th><th>ราคาต่อหน่วย</th><th>จำนวน</th><th>ราคารวม</th><th>Action</th></tr>";
      $total = 0;
      foreach ($_SESSION["cart"] as $product) {
        $subtotal = $product["price"] * $product["quantity"];
        $total += $subtotal;
        echo "<tr>";
        echo "<td><img src='image/" . $product["image"] . "' class='img'></td>";
        echo "<td>" . $product["name"] . "</td>";
        echo "<td>" . $product["price"] . " บาท</td>";
        echo "<td><input type='number' min='1' value='" . $product["quantity"] . "' onchange='updateQuantity(\"" . $product["code"] . "\", this.value)' class='quantity-input'></td>";
        echo "<td>" . $subtotal . " บาท</td>";
        echo "<td><a href='Cart.php?action=remove&code=" . $product["code"] . "' class='button'>ลบ</a></td>";
        echo "</tr>";
      }
      echo "<tr><td colspan='5' align='right'><b>ยอดรวมทั้งหมด</b></td><td>" . $total . " บาท</td></tr>";
      echo "</table>";
      echo "<div class='btn-container'><button type='submit' class='button button1' name='submit_order'> สั่งซื้อสินค้า </button></div>";

    } else {
      echo "<p class='empty-cart-msg'>ไม่มีสินค้าในตะกร้า</p>";
    }
    ?>

    <script>
      function updateQuantity(code, quantity) {
        window.location.href = "Cart.php?action=update&code=" + code + "&quantity=" + quantity;
      }
    </script>

  </div>
</body>

</html>
