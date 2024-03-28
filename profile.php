<?php
session_start();
include "db_config.php";
include "header.php";
if (!isset($_SESSION["CustNo"])) {
    header("Location: login.html");
    exit();
}

$query = "SELECT * FROM Cust WHERE CustNo = ".$_SESSION["CustNo"];
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["Email"]);
    $custName = mysqli_real_escape_string($conn, $_POST["CustName"]);
    $sex = mysqli_real_escape_string($conn, $_POST["Sex"]);
    $address = mysqli_real_escape_string($conn, $_POST["Address"]);
    $tel = mysqli_real_escape_string($conn, $_POST["Tel"]);

    $update_query = "UPDATE Cust SET Email='$email', CustName='$custName', Sex='$sex', Address='$address', Tel='$tel' WHERE CustNo=".$_SESSION["CustNo"];
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
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Edit Profile</title>
    <link rel="stylesheet" href="Styles/profile.css">
</head>

<body>
    <div class="container">
    <h2>Edit Profile</h2>
    <?php if (isset($error_message)) echo "<p>$error_message</p>"; ?>
    <form action="" method="post">

        <label for="Username">Username:</label><br>
        <input type="text" id="Username" name="Username" value="<?php echo $row['Username']; ?>" ><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="Email" value="<?php echo $row['Email']; ?>" ><br><br>

        <label for="CustName">Name:</label><br>
        <input type="text" id="CustName" name="CustName" value="<?php echo $row['CustName']; ?>" ><br><br>

        <label for="Sex">Sex:</label><br>
        <input type="radio" id="male" name="Sex" value="M" <?php if ($row['Sex'] == 'M') echo 'checked'; ?>>
        <label for="male">Male</label>
        <input type="radio" id="female" name="Sex" value="F" <?php if ($row['Sex'] == 'F') echo 'checked'; ?>>
        <label for="female">Female</label><br><br>

        <label for="Address">Address:</label><br>
        <input type="text" id="Address" name="Address" value="<?php echo $row['Address']; ?>"><br><br>

        <label for="Tel">Phone:</label><br>
        <input type="tel" id="Tel" name="Tel" value="<?php echo $row['Tel']; ?>"><br><br>

        <input type="submit" value="Update" class="btn">
    </form>
</div>
</body>
</html>

<?php
mysqli_close($conn);
?>
