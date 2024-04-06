<?php
require_once "db_config.php";

$username = "admin1";
$password = "123";
$role = "admin";

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$query = "INSERT INTO cust (Username, Password, Role) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sss", $username, $hashed_password, $role);
$result = mysqli_stmt_execute($stmt);

if ($result) {
    echo "Admin user added successfully.";
} else {
    echo "Error adding admin user: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
