<?php 
include "db_config.php";

$a1 = $_GET['a1'];
$a2 = $_GET['a2'];
$a3 = $_GET['a3'];
$a4 = $_GET['a4'];
$a5 = $_GET['a5'];

    /*run update*/
    $stmt = mysqli_prepare($conn, "UPDATE Cust SET
                        CustName = ?, Sex = ?, Address = ?, Tel = ?
                            WHERE CustNo=?");
    mysqli_stmt_bind_param($stmt, "sssss", $a2, $a3, $a4, $a5, $a1);

    /*check for errors*/
    if(!mysqli_execute($stmt)) {
        /*error*/
        echo "Error";
    } else {
        echo "Update data = <font color = red>'$a1'</font> is Successful";
    }

    /*close connection*/
    mysqli_close($conn);
    header("Location: ../Show_Customer.php");

?>