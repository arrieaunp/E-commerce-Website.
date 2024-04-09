<?php
session_start();
include "db_config.php";
include "header.php";
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (!isset($_COOKIE['token'])) {
    header("Location: login.html");
    exit();
}

$secret_key = $_ENV['SECRETKEY'];

try {
    $decoded = JWT::decode($_COOKIE['token'], new Key($secret_key, 'HS256'));
    $Username = $decoded->data->Username;

    $query = "SELECT * FROM Cust WHERE Username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $Username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="Styles/profile.css">
</head>
<body>
    <div class="profile-container">
        <h2>Edit Profile</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="Username">Username:</label>
                <input type="text" id="Username" name="Username" value="<?php echo $row['Username']; ?>" disabled>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="Email" value="<?php echo $row['Email']; ?>">
            </div>

            <div class="form-group">
                <label for="CustName">Name:</label>
                <input type="text" id="CustName" name="CustName" value="<?php echo $row['CustName']; ?>">
            </div>

            <div class="form-group">
                <label for="Sex">Sex:</label>
                <input type="radio" id="male" name="Sex" value="M" <?php if ($row['Sex'] == 'M') echo 'checked'; ?>>
                <label for="male">Male</label>
                <input type="radio" id="female" name="Sex" value="F" <?php if ($row['Sex'] == 'F') echo 'checked'; ?>>
                <label for="female">Female</label>
            </div>

            <div class="form-group">
                <label for="Address">Address:</label>
                <input type="text" id="Address" name="Address" value="<?php echo $row['Address']; ?>">
            </div>

            <div class="form-group">
                <label for="Tel">Phone:</label>
                <input type="tel" id="Tel" name="Tel" value="<?php echo $row['Tel']; ?>">
            </div>

            <input type="submit" value="Update" class="btn">
        </form>
    </div>
</body>
</html>
<?php
    } else {
        header("Location: login.html");
        exit();
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    //header('Location: login.html');
    exit();
}
?>
