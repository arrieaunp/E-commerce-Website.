<?php
require_once '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

function hasPermission($role) {
    return $role == 'admin' || $role == 'superadmin';
}

if (isset($_COOKIE['token'])) {
    $jwt = $_COOKIE['token'];
    $secret_key = $_ENV['SECRETKEY'];

    try {
        $decoded = JWT::decode($_COOKIE['token'], new Key($secret_key, 'HS256'));
        if (hasPermission($decoded->data->Role)) {
        } else {
            echo "Error: You don't have permission to access this page.";
            exit();
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "Error: Token not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Customer Reports</title>
</head>
<body>

    <header>
        <h1>Customer Reports</h1>
    </header>
    <center><span id="datetime"></span></center>
<script>
      function updateDateTime() {
        const now = new Date();
        const currentDateTime = now.toLocaleString();
        document.querySelector('#datetime').textContent = currentDateTime;
      }
      setInterval(updateDateTime, 1000);
</script>

<form action="" method="post">
    <label for="searchInput">ค้นหาลูกค้า:</label>
    <input type="text" id="searchInput" name="searchInput">
    <button type="submit" class="searchButton">Search</button>
</form>

<?php
include "../db_config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchInput = mysqli_real_escape_string($conn, $_POST["searchInput"]);
    $searchQuery = "SELECT * FROM Cust WHERE CustNo LIKE '%$searchInput%' OR CustName LIKE '%$searchInput%' OR Address LIKE '%$searchInput%'";
    $cur = mysqli_query($conn, $searchQuery);
} else {
    $cur = mysqli_query($conn, "SELECT * FROM Cust WHERE 1");
}

$canEdit = hasPermission($decoded->data->Role == 'superadmin');
?>

<a href="/Fullstack/Customer/Insert_Customer/Insert_Customer.html" target="_blank">
    <button class="button button1">Insert Customer</button>
</a>

<?php if ($canEdit): ?>
    <form action="Delete_Customer/Delete_Customer.php" method="post">
        <button type="submit" name="deleteSelected" class="button button1">Delete Selected</button>
    </form>
<?php endif; ?>

<table>
    <tr>
        <th></th>
        <th>CustNo</th>
        <th>CustName</th>
        <th>Sex</th>
        <th>Address</th>
        <th>Tel</th>
        <?php if ($canEdit): ?>
            <th>Update</th>
            <th>Delete</th>
        <?php endif; ?>
    </tr>

<?php while ($row = mysqli_fetch_array($cur)) : ?>
    <tr>
        <td class="checkboxColumn"><input type="checkbox" name="selectedCustomers[]" value="<?= $row['CustNo'] ?>"></td>
        <td><?= $row['CustNo'] ?></td>
        <td><?= $row['CustName'] ?></td>
        <td><?= $row['Sex'] ?></td>
        <td><?= $row['Address'] ?></td>
        <td><?= $row['Tel'] ?></td>
        <?php if ($canEdit): ?>
            <td><a href="/Fullstack/Customer/Update_Customer/Update_Customer.php?a1=<?= $row['CustNo'] ?>">Update</a></td>
            <td><a href="/Fullstack/Customer/Delete_Customer/Delete_Customer.php?a1=<?= $row['CustNo'] ?>">Delete</a></td>
        <?php endif; ?>
    </tr>
<?php endwhile; ?>
</table>
</body>
</html>
