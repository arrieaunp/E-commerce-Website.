<?php
session_start();
include "../header.php";
include "../db_config.php";

if (!isset($_SESSION["CustNo"])) {
    header("Location: login.html");
    exit();
}

$query = "SELECT * FROM Cust WHERE CustNo = ".$_SESSION["CustNo"];
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/payment.css">
    <title>Payment</title>
</head>

<body>
<?php
    include "header.php";
?>

    <div class="payment-container">
        <h1>วิธีการชำระเงิน</h1>
        <form action="process_payment.php" method="post">

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

            <button type="submit">ดำเนินการชำระเงิน</button>
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
