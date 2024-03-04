<?php
require_once "db_config.php";
session_start();  

function generateSalt() {
    return base64_encode(random_bytes(32));
}

// Function to hash password with salt
function hashPassword($password, $salt) {
    return password_hash($password . $salt, PASSWORD_DEFAULT);
}

function isStrongPassword($password) {
    $min_length = 8;
    
    if (strlen($password) < $min_length) {
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
    if (!preg_match('/[\W]/', $password)) {
        return false;
    }
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

    if(!isStrongPassword($password)) {
        echo "<script>alert('Password does not meet the criteria for a strong password.'); window.location='SignUp.html';</script>";
        exit();
    }

    $salt = generateSalt();
    $hashed_password = hashPassword($password, $salt);

    $insert_query = "INSERT INTO Cust (Username, Salt_pwd, Password, CustName, Sex, Address, Tel) VALUES ('$Username', '$salt', '$hashed_password', '$CustName', '$Sex', '$Address', '$Tel')";

    if (mysqli_query($conn, $insert_query)) {
        echo "Registration successful!";
        header("Location: login.html");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

}
mysqli_close($conn);
?>