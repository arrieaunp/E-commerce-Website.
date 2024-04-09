<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (isset($_COOKIE['token'])) {
    $jwt = $_COOKIE['token'];
    $secret_key = $_ENV['SECRETKEY'];

    try {
        $decoded = JWT::decode($_COOKIE['token'], new Key($secret_key, 'HS256'));
        if ($decoded->data->Role == "admin" || $decoded->data->Role == "superadmin") {
            
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
?>
<nav role='navigation'>
  <ul class="main">
    <li class="dashboard"><a href="Adminpage.php">Dashboard</a></li>
    <li class="users"><a href="../Customer/Show_Customer.php">Customer</a></li>
    <li class="write"><a href="../Stock/Show_Stock.php">Stock</a></li>
    <li class="comments"><a href="Income_report.php">Income Report</a></li>
    <li class="comments"><a href="ProductSales_report.php">Product Sales Report</a></li>
    <?php if ($decoded->data->Role == "superadmin"): ?>
      <li class="add-admin"><a href="Add_Admin.php">Add Admin</a></li>
    <?php endif; ?>
  </ul>
</nav>
