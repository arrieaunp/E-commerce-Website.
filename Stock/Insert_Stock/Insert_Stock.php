<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Insert_styles.css">
    <title>Insert Data</title>
</head>
<body>

    <header>
        <h1>Insert Data</h1>
    </header>

    <div class="container">
        <?php
        include "db_config.php";
        
        $a1 = $_GET['a1'];
        $a2 = $_GET['a2'];
        $a3 = $_GET['a3'];
        $a4 = $_GET['a4'];
        $a5 = $_GET['a5'];

        /* run insert */
        $stmt = mysqli_prepare($conn, "INSERT INTO Stock (ProductCode, ProductName, PricePerUnit, StockQty, ProductImg) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $a1, $a2, $a3, $a4, $a5);

        /* execute and check for errors */
        if (!mysqli_execute($stmt)) {
            echo '<p class="error-message">Error</p>';
        } else {
            echo '<p class="success-message">Insert data=<span style="color: red;"> \'' . $a1 . '\'</span> is Successful</p>';
        }

        /* close connection */
        mysqli_close($conn);
        ?>
    </div>
<a href="http://localhost/bb/Fullstack/Stock/Show_Stock.php" target="_blank">
    <button class="button button1">ฺBack to Stock</button>
</a>

</body>
</html>
