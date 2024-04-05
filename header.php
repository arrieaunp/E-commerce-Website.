<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Bar</title>
    <link rel="stylesheet" href="Styles/header.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="index.php">Lazadee Shoppa</a>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="index.php">หน้าแรก</a></li>
                    <li><a href="Order_history.php">ประวัติการซื้อ</a></li>
                    <li><a href="Cart.php">ตะกร้าสินค้า</a></li>
                </ul>
            </nav>
            <div class="account">
                <?php include 'account_info.php'; ?>
            </div>
        </div>
    </header>
</body>
</html>
