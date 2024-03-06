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
        <?php
            $code = $_GET['a1'];
            $conn = mysqli_connect("localhost", "root", "", "mydb");
            $stmt = mysqli_prepare($conn, "DELETE FROM Stock WHERE ProductCode = ?");
            mysqli_stmt_bind_param($stmt, "s", $code);
            if (!mysqli_execute($stmt)) {
                echo '<p class="error-message">Error</p>';
            } else {
                echo '<p class="success-message">Delete data=<span style="color: red;"> \'' . $code . '\'</span> is Successful</p>';
            }
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        ?>
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteSelected'])) {
                $cx = mysqli_connect("localhost", "root", "", "mydb");
                $selectedProducts = $_POST['selectedProducts'];
                foreach ($selectedProducts as $ProductCode) {
                    $deleteQuery = "DELETE FROM Stock WHERE ProductCode = '$ProductCode'";
                    mysqli_query($cx, $deleteQuery);
                }
                mysqli_close($cx);
                exit();
            }
        ?>   

    </div>
<a href="http://localhost/bb/Fullstack/Stock/Show_Stock.php" target="_blank">
    <button class="button button1">ฺBack to Stock</button>
</a>

</body>
</html>
