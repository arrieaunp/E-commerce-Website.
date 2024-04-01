<?php
include 'db_config.php';

if(isset($_SESSION["Username"])){
    echo '
    <a href="profile.php" class="logout-btn">'.$_SESSION["CustName"].'</a>
    <a href="logout.php" class="logout-btn">ออกจากระบบ</a>';
} else if(isset($_SESSION['google_loggedin']) == TRUE){
    echo '
    <a href="profile.php" class="logout-btn">'.$_SESSION['google_name'].'</a>
    <a href="logout.php" class="logout-btn">ออกจากระบบ</a>';
}  else {
    echo '
    <a href="login.html" class="login-btn">เข้าสู่ระบบ</a>
    <a href="SignUp.html" class="signup-btn">สมัครสมาชิก</a>';
}
?>
