<?php
require_once "db_config.php";

function isStrongPassword($password) {
    $min_length = 8;
    if (strlen($password) < $min_length ) {
        return false;
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }
    if (!preg_match('/[a-z]/', $password)) {
        return false;
    }
    if (!preg_match('/[0-9]/', $password)) {
        return false;
    }
    //อักขระพิเศษ
    if (!preg_match('/[\W]/', $password)) {
        return false;
    }
    //ไม่มีตัวอักษรซ้ำซ้อน
    if (preg_match('/(.)\1{2,}/', $password)) {
        return false;
    }
    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Username = mysqli_real_escape_string($conn, $_POST["Username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $CustName = mysqli_real_escape_string($conn, $_POST["CustName"]);
    $Sex = mysqli_real_escape_string($conn, $_POST["Sex"]);
    $Address = mysqli_real_escape_string($conn, $_POST["Address"]);
    $Tel = mysqli_real_escape_string($conn, $_POST["Tel"]);

    $check_query = "SELECT * FROM Cust WHERE Username = '$Username'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Username already exists. Please choose a different username.')</script>";
        echo "<script>window.location = 'SignUp.html'</script>";
        exit();
    }

    $option = ['cost' => 10];

    $hashed_password = password_hash($password, PASSWORD_BCRYPT, $option);

    $insert_query = "INSERT INTO Cust (Username, Password, CustName, Address, Tel) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "sssss", $Username, $hashed_password, $CustName, $Address, $Tel);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: login.html");
        exit();
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt); 

}
mysqli_close($conn);
?>