<?php
require_once "../db_config.php";
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (isset($_COOKIE['token'])) {
    $jwt = $_COOKIE['token'];
    $secret_key = $_ENV['SECRETKEY'];

    try {
        $decoded = JWT::decode($_COOKIE['token'], new Key($secret_key, 'HS256'));
        $CustNo = $decoded->data->UserId;
        if ($decoded->data->Role == "superadmin") {
            
        } else {
            echo "Error: You don't have permission to access this page.";
            //header("Location: ../login.html");
            exit();
        }
    } catch (Exception $e) {
        "Error: " . $e->getMessage();
        //header("Location: ../login.html");
        exit();
    }
} else {
    echo "Error: Token not found.";
    //header("Location: ../login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $CustNo = uniqid('admin_');
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $role = mysqli_real_escape_string($conn, $_POST["role"]);

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $query = "INSERT INTO cust (CustNo, Username, Password, Role) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss",$CustNo, $username, $hashed_password, $role);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo "Admin user added successfully.";
    } else {
        echo "Error adding admin user: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <link rel="stylesheet" href="Back_Styles/add_admin.css">
</head>
<body>
    <div class="container">
        <h2 class="title">Add Admin</h2>
        <form action="add_admin.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="superadmin">Superadmin</option>
                </select>
            </div>
            <button type="submit">Add Admin</button>
        </form>
    </div>
</body>
</html>


