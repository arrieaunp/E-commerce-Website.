<?php
require_once "db_config.php";
session_start();

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
      $_SESSION["CustNo"] = $row["CustNo"];
      $_SESSION["Username"] = $row["Username"];
      $_SESSION["CustName"] = $row["CustName"];
      $_SESSION["Address"] = $row["Address"];
      $_SESSION["Tel"] = $row["Tel"];

      if ($row["Role"] == "admin") {
        $_SESSION["admin"] = true;
      }

      header("Location: " . ($row["Role"] == "admin" ? "AdminPage/Adminpage.php" : "index.php"));
      exit();
    } else {
      echo "Invalid username or password"; 
    }
  } else {
    echo "Invalid username or password";
  }

  mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
