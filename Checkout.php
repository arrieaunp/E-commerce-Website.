<?php
session_start();
include "db_config.php";
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$secret_key = $_ENV['SECRETKEY'];

if (!isset($_COOKIE['token'])) {
  header('location:index.php');
  exit();
}

try {
    $decoded = JWT::decode($_COOKIE['token'], new Key($secret_key, 'HS256'));
    $CustName = isset($decoded->data->CustName) ? $decoded->data->CustName : "";
    $Address = isset($decoded->data->Address) ? $decoded->data->Address : "";
    $Tel = isset($decoded->data->Tel) ? $decoded->data->Tel : "";
  } catch (Exception $e) {
    header('location:login.html');
    exit();
  }
include "header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="Styles/Checkout.css">

  <title>Checkout and Payment</title>
</head>

<body>
<div class="grid-container">

    <div class="checkout-container">
    <form action="process_payment.php" method="post">

    <?php
if (isset($_COOKIE['token'])) {
    echo "<h2>ที่อยู่สำหรับจัดส่ง</h2>",
        "<div class='input-container'>
            <label class='input-label' for='CustName'><b>ชื่อ :</b></label>
            <input class='input-field' type='text' id='CustName' name='CustName' value='" . $CustName . "'>
        </div>",
        "<div class='input-container'>
            <label class='input-label' for='Address'><b>ที่อยู่ :</b></label>
            <input class='input-field' type='text' id='Address' name='Address' value='" . $Address . "'>
        </div>",
        "<div class='input-container'>
            <label class='input-label' for='Tel'><b>เบอร์โทร :</b></label>
            <input class='input-field' type='text' id='Tel' name='Tel' value='" . $Tel . "'>
        </div>";
} else if (isset($_SESSION['google_loggedin']) == TRUE) {
    echo "<h2>ที่อยู่สำหรับจัดส่ง</h2>",
        "<div class='input-container'>
            <label class='input-label' for='CustName'><b>ชื่อ :</b></label>
            <input class='input-field' type='text' id='CustName' name='CustName' value='" . $_SESSION['google_name'] . "'>
        </div>",
        "<div class='input-container'>
            <label class='input-label' for='Address'><b>ที่อยู่ :</b></label>
            <input class='input-field' type='text' id='Address' name='Address' value='" . $_SESSION["Address"] . "'>
        </div>",
        "<div class='input-container'>
            <label class='input-label' for='Tel'><b>เบอร์โทร :</b></label>
            <input class='input-field' type='text' id='Tel' name='Tel' value='" . $_SESSION["Tel"] . "'>
        </div>";
} else if (isset($_SESSION['facebook_loggedin']) == TRUE) {
    echo "<h2>ที่อยู่สำหรับจัดส่ง</h2>",
        "<div class='input-container'>
            <label class='input-label' for='CustName'><b>ชื่อ :</b></label>
            <input class='input-field' type='text' id='CustName' name='CustName' value='" . $_SESSION['facebook_name'] . "'>
        </div>",
        "<div class='input-container'>
            <label class='input-label' for='Address'><b>ที่อยู่ :</b></label>
            <input class='input-field' type='text' id='Address' name='Address' value='" . $_SESSION["Address"] . "'>
        </div>",
        "<div class='input-container'>
            <label class='input-label' for='Tel'><b>เบอร์โทร :</b></label>
            <input class='input-field' type='text' id='Tel' name='Tel' value='" . $_SESSION["Tel"] . "'>
        </div>";
} else {
    echo "<h2>ที่อยู่สำหรับจัดส่ง</h2>",
        "<div class='input-container'>
            <label class='input-label' for='CustName'><b>ชื่อ :</b></label>
            <input class='input-field' type='text' id='CustName' name='CustName'>
        </div>",
        "<div class='input-container'>
            <label class='input-label' for='Address'><b>ที่อยู่ :</b></label>
            <input class='input-field' type='text' id='Address' name='Address'>
        </div>",
        "<div class='input-container'>
            <label class='input-label' for='Tel'><b>เบอร์โทร :</b></label>
            <input class='input-field' type='text' id='Tel' name='Tel'>
        </div>";
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
    </div>

<div class="payment-container">
        <h2>Payment</h1>

            <div class="payment-method">
                <input type="radio" id="pay_now" name="payment_method" value="pay_now" onclick="toggleBankOptions()">
                <label for="pay_now">Mobile Banking</label><br>
                <div id="bank-options" style="display: none;">
                    <div class="bank-option">
                        <input type="checkbox" name="bank_option" value="kbank">
                        <img class="icon" src="image/kplus.png" alt="KPlus Logo"> K PLUS<br>
                    </div>
                    <div class="bank-option">
                        <input type="checkbox" name="bank_option" value="scb">
                        <img class="icon" src="image/scb.png" alt="SCB Logo"> SCB Easy<br>
                    </div>
                    <div class="bank-option">
                        <input type="checkbox" name="bank_option" value="bangkok">
                        <img class="icon" src="image/bkk.avif" alt="bkk Logo"> Bangkok Bank Mobile Banking<br>
                    </div>
                    <div class="bank-option">
                        <input type="checkbox" name="bank_option" value="krungth">
                        <img class="icon" src="image/Krungth.jpeg" alt="KrungTH Logo"> Krungthai NEXT<br>
                    </div>
                </div>
            </div>

            <div class="payment-method">
                <input type="radio" id="pay_later" name="payment_method" value="pay_later">
                <label for="pay_later">เก็บเงินปลายทาง</label><br><br>
            </div>
            <center>
            <button type="submit">Check out</button>
            </center>

    </div>

</form>
</div>

  <script>
        function toggleBankOptions() {
            var bankOptions = document.getElementById("bank-options");
            if (document.getElementById("pay_now").checked) {
                bankOptions.style.display = "block";
            } else {
                bankOptions.style.display = "none";
            }
        }
    </script>

</body>
</html>
