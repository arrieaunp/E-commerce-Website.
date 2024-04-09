<?php
include 'db_config.php';
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$secret_key = $_ENV['SECRETKEY'];

if (isset($_COOKIE['token'])) {
    try {
        $decoded = JWT::decode($_COOKIE['token'], new Key($secret_key, 'HS256'));
        $Username = $decoded->data->Username;
    } catch (Exception $e) {
        header('location:login.html');
        exit();
    }
}
  
if(isset($Username)){
    echo '
    <a href="profile.php" class="logout-btn">'.$Username.'</a>
    <a href="logout.php" class="logout-btn">ออกจากระบบ</a>';
} else if(isset($_SESSION['google_loggedin']) == TRUE){
    echo '
    <a href="profile.php" class="logout-btn">'.$_SESSION['google_name'].'</a>
    <a href="logout.php" class="logout-btn">ออกจากระบบ</a>';
} else if(isset($_SESSION['facebook_loggedin']) == TRUE){
    echo '
    <a href="profile.php" class="logout-btn">'.$_SESSION['facebook_name'].'</a>
    <a href="logout.php" class="logout-btn">ออกจากระบบ</a>';

}
  else {
    echo '
    <a href="login.html" class="login-btn">เข้าสู่ระบบ</a>
    <a href="SignUp.html" class="signup-btn">สมัครสมาชิก</a>';
}
?>
