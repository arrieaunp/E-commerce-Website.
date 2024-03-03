<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/payment.css">
    <title>Payment</title>
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
