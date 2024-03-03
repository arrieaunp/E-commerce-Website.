<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="Styles/Menu.css">
  <title>Menu</title>
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

    <h1>รายการสินค้า</h1>
    <form method="GET" action="Menu.php">
      <input type="text" name="search" placeholder="ค้นหาสินค้า..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
      <button type="submit">ค้นหา</button>
    </form>
    <form method="GET" action="Cart.php">
    <?php
    session_start();
    $conn = mysqli_connect("localhost", "root", "", "mydb");

    if (isset($_GET['search'])) {
      $search = mysqli_real_escape_string($conn, $_GET['search']);
      $query = "SELECT * FROM `Stock` WHERE `ProductName` LIKE '%$search%'";
    } else {
      $query = "SELECT * FROM `Stock`";
    }

    $cur = mysqli_query($conn, "SELECT * FROM `Stock` WHERE 1");
    while ($row = mysqli_fetch_array($cur)) {
      $name1 = $row["ProductCode"];
      $name2 = $row["ProductName"];                
      $name3 = $row["PricePerUnit"];
      $name4 = $row["StockQty"];
      $name6 = $row["ProductImg"];

      echo  "<div class='product-container'>",
            "<div class='product-card'>",
            "<img class='img' src='image/$name6'>",
            "<p> <b>$name2</b></p>",
            "<p><b> ราคา : </b>$name3 บาท</p>",
            "<p><b>สินค้าคงเหลือ : </b>$name4 ชิ้น</p>",
            "<button type='submit' class='button button1' value='$name1' name='selected'> Add To Cart </button>",
            "</div>",
            "</div>";
    }
    ?>
  </center>

</body>
</html>
