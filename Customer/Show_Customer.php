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
        <button type="submit" class="searchButton">
</form>

<?php
    $cx = mysqli_connect("localhost", "root", "", "mydb");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchInput = mysqli_real_escape_string($cx, $_POST["searchInput"]);
        $searchQuery = "SELECT * FROM Customer WHERE CustNo LIKE '%$searchInput%' OR CustName LIKE '%$searchInput%' OR Address LIKE '%$searchInput%'";
        $cur = mysqli_query($cx, $searchQuery);
    } else {
        $cur = mysqli_query($cx, "SELECT * FROM Customer WHERE 1");
    }
?>

<a href="http://localhost/bb/Customer/Insert_Customer/Insert_Customer.html" target="_blank">
    <button class="button button1">Insert Customer</button>
</a>

<form action="Delete_Customer/Delete_Customer.php" method="post">
<button type="submit" name="deleteSelected" class="button button1">Delete Selected</button>


<table>
    <tr>
        <th></th>
        <th>CustNo</th>
        <th>CustName</th>
        <th>Sex</th>
        <th>Address</th>
        <th>Tel</th>
        <th>Update</th>
        <th>Delete</th>
    </tr>

<?php
while ($row = mysqli_fetch_array($cur)) {
    echo '<tr>
            <td class="checkboxColumn"><input type="checkbox" name="selectedCustomers[]" value="' . $row['CustNo'] . '"></td>
            <td>' . $row['CustNo'] . '</td>
            <td>' . $row['CustName'] . '</td>
            <td>' . $row['Sex'] . '</td>
            <td>' . $row['Address'] . '</td>
            <td>' . $row['Tel'] . '</td>
            <td><a href="http://localhost/bb/Customer/Update_Customer/Update_Customer.php?a1=' . $row['CustNo'] . '">Update</a></td>
            <td><a href="http://localhost/bb/Customer/Delete_Customer/Delete_Customer.php?a1=' . $row['CustNo'] . '">Delete</a></td>
          </tr>';
}
?>
</table>
</body>
</html>