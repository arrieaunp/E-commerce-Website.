<?php
require_once "../db_config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $role = mysqli_real_escape_string($conn, $_POST["role"]);

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
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <link rel="stylesheet" href="Back_Styles/add_admin.css"> <!-- You can link your CSS file here -->
</head>
<body>
    <div class="container">
        <h2 class="title">Add Admin</h2>
        <form action="add_admin_process.php" method="POST">
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


