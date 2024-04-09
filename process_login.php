<?php
require_once "db_config.php";
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $Username = mysqli_real_escape_string($conn, $_POST["Username"]);

  $query = "SELECT * FROM Cust WHERE Username = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "s", $Username);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);

    if (password_verify($_POST["password"], $row["Password"])) {
      $payload = array(
        'iat' => time(),
        'exp' => strtotime("+1 hour"),
        'data' => array(
          'UserId' => $row['CustNo'],
          'Role' => $row['Role'],
          'Username' => $row['Username'],
          'CustName' => $row['CustName'],
          'Address' => $row['Address'],
          'Tel' => $row['Tel'],
        ),
      );

      $secret_key = $_ENV['SECRETKEY'];

      $jwt = JWT::encode($payload, $secret_key, 'HS256');
      setcookie("token", $jwt, time() + 3600, "/", "", true, true);

      header("Location: " . "index.php");
      exit();
    } else {
        $error = "Invalid username or password"; 
    }
  } else {
      $error = "Invalid username or password";
  }

  if($error !== '')
  {
    echo '<div class="alert alert-danger">'. $error . '</div>';
  }

  mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
