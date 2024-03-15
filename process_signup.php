<?php
require_once "db_config.php";
session_start();

// Define your pepper
define('PEPPER', 'TestPepperValue');

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

function generateSalt() {
    return password_hash(random_bytes(10), PASSWORD_BCRYPT);
}

// Function to hash password with salt and pepper
function hashPassword($password, $salt) {
    return password_hash(PEPPER . $password . $salt, PASSWORD_BCRYPT);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Username = mysqli_real_escape_string($conn, $_POST["Username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $CustName = mysqli_real_escape_string($conn, $_POST["CustName"]);
    $Sex = mysqli_real_escape_string($conn, $_POST["Sex"]);
    $Address = mysqli_real_escape_string($conn, $_POST["Address"]);
    $Tel = mysqli_real_escape_string($conn, $_POST["Tel"]);

    if (!isStrongPassword($password)) {
        echo "<script>alert('Password must be at least 8 characters long, <br>
        contain at least one uppercase letter, one lowercase letter, <br>
        one digit, and one special character. <br>
        It should not contain repeated characters.')</script>";
        echo "<script>window.location = 'SignUp.html'</script>";
        exit(); 
        }

    $salt = generateSalt();
    $hashed_password = hashPassword($password, $salt);

    $insert_query = "INSERT INTO Cust (Username, SaltPwd, Password, CustName, Sex, Address, Tel) VALUES ('$Username', '$salt', '$hashed_password', '$CustName', '$Sex', '$Address', '$Tel')";

    if (mysqli_query($conn, $insert_query)) {
        header("Location: login.html");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>