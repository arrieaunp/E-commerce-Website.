<?php
session_start();
include "db_config.php";
include "header.php";

if (isset($_SESSION["CustNo"])) {
    $query = "SELECT * FROM Cust WHERE CustNo = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["CustNo"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} elseif (isset($_SESSION['google_loggedin']) && $_SESSION['google_loggedin'] == TRUE) {
    $query = "SELECT * FROM Cust WHERE Email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $_SESSION["google_email"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}

$row = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["Email"]);
    $custName = mysqli_real_escape_string($conn, $_POST["CustName"]);
    $sex = mysqli_real_escape_string($conn, $_POST["Sex"]);
    $address = mysqli_real_escape_string($conn, $_POST["Address"]);
    $tel = mysqli_real_escape_string($conn, $_POST["Tel"]);

    $update_query = "UPDATE Cust SET Email='$email', CustName='$custName', Sex='$sex', Address='$address', Tel='$tel' WHERE CustNo=" . $_SESSION["CustNo"];
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        header("Location: profile.php");
        exit();
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}
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
        <?php if (isset($error_message)) echo "<p>$error_message</p>"; ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="Username">Username:</label>
                <input type="text" id="Username" name="Username" value="<?php echo $row['Username']; ?>">
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
mysqli_close($conn);
?>
