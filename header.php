<header>
    <nav>
      <ul>
        <li><a href="Menu.php">หน้าแรก</a></li>
        <li><a href="Order_history.php">ประวัติการซื้อ</a></li>
        <li><a href="Cart.php">ตะกร้าสินค้า</a></li>
        <?php
        include 'db_config.php';

        if(isset($_SESSION["CustNo"])){
            $sql = "SELECT CustName FROM Cust WHERE CustNo='".$_SESSION["CustNo"]."'";
            $query = mysqli_query($conn,$sql);
            $row=mysqli_fetch_array($query);
                echo '
                <li class="dropdownn" id="account-dropdown">
                  <a href="#" class="dropbtn">'.$row["CustName"].'</a>
                  <div class="dropdownn-content">
                  <div class="account-box">
                    <a href="">โปรไฟล์</a>
                    <a href="logout.php"><br>ออกจากระบบ</a>
                  </div>
                  </div>
                </li>';
        }else if(isset($_SESSION["google_email"])){
          $sql2 = "SELECT CustName FROM cust WHERE Email='".$_SESSION["google_email"]."'";
          $query2 = mysqli_query($conn,$sql2);
          if($query2 && mysqli_num_rows($query2) > 0){
              $row=mysqli_fetch_array($query2);
              echo '
              <li class="dropdownn" id="account-dropdown">
                <a href="#" class="dropbtn">'.$row["CustName"].'</a>
                <div class="dropdownn-content">
                <div class="account-box">
                  <a href="">โปรไฟล์</a>
                  <a href="logout.php"><br>ออกจากระบบ</a>
                </div>
                </div>
              </li>';
          } 
        }
         else {
            echo '
            <li class="dropdownn" id="account-dropdown">
              <a href="#" class="dropbtn">บัญชี</a>
              <div class="dropdownn-content">
              <div class="account-box">
                <a href="login.html">เข้าสู่ระบบ</a>
                <a href="SignUp.html"><br>สมัครสมาชิก</a>
              </div>
              </div>
            </li>';
        }
        ?>
      </ul>
    </nav>
  </header>