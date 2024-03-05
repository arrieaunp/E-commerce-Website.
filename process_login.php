<?php
require_once "db_config.php";
session_start();  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Username = mysqli_real_escape_string($conn, $_POST["Username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    if ($Username == "admin" && $password == "admin") {
        $_SESSION["admin"] = true;
        header("Location: AdminPage/AdminPage.php");
        exit();
    }

    $query = "SELECT * FROM Cust WHERE Username = '$Username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        $_SESSION["CustNo"] = $row["CustNo"];
        $_SESSION["CustName"] = $row["CustName"];
        $_SESSION["Address"] = $row["Address"];
        $_SESSION["Tel"] = $row["Tel"];

        header("Location: Menu.php");
        exit();
    } 
    else {
        header("Location: login.html");
        exit();
    }
}
mysqli_close($conn);
?>
