<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Delete_styles.css">
    <title>Delete Data</title>

</head>
<body>

    <header>
        <h1>Delete Data</h1>
    </header>

    <div class="container">
    <!-- from Delete_Customer.html -->
    <?php
        $code = $_GET['a1'];
        $conn = mysqli_connect("localhost", "root", "", "mydb");
        $stmt = mysqli_prepare($conn, "DELETE FROM Customer WHERE CustNo = ?");
        mysqli_stmt_bind_param($stmt, "s", $code);        
        /* check error */
        if (!mysqli_execute($stmt)) {
            echo '<p class="error-message">Error</p>';
        } else {
            echo '<p class="success-message">Delete data=<span style="color: red;"> \'' . $code . '\'</span> is Successful</p>';
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    ?>
    <!-- from Show_Customer.php -->
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteSelected'])) {
            $cx = mysqli_connect("localhost", "root", "", "mydb");
            $selectedCustomers = $_POST['selectedCustomers'];
            foreach ($selectedCustomers as $custNo) {
                $deleteQuery = "DELETE FROM Customer WHERE CustNo = '$custNo'";
                mysqli_query($cx, $deleteQuery);
            }
            mysqli_close($cx);
            exit();
        }
    ?>   
    </div>
</body>
</html>
