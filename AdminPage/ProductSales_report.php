<?php
ob_start();
include "../db_config.php";
include "sidenav.php";
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    //Top selling
    $product_sales_query = "SELECT od.ProductCode, s.ProductName, SUM(od.Qty) AS total_quantity, SUM(od.LineTotal) AS total_sales 
                        FROM OrderDetail od 
                        INNER JOIN Stock s ON od.ProductCode = s.ProductCode 
                        INNER JOIN OrderHeader oh ON od.OrderId = oh.OrderId 
                        WHERE oh.OrderDate BETWEEN '$start_date' AND '$end_date' 
                        GROUP BY od.ProductCode 
                        ORDER BY total_sales DESC";
    $product_sales_result = mysqli_query($conn, $product_sales_query);

    // Store result in an array for later use
    $product_sales_data = [];
    while ($row = mysqli_fetch_assoc($product_sales_result)) {
        $product_sales_data[] = $row;
    }
}

function generatePDF($data, $start_date, $end_date) {
    require_once('vendor/TCPDF-main/tcpdf.php');
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle('Product Sales Report');
    $pdf->SetSubject('Product Sales Report');
    $pdf->SetKeywords('TCPDF, PDF, Product Sales, Report');

    $pdf->AddPage();
    $pdf->SetFont('thsarabun', '', 12);

    $html = '<h2>Product Sales Report</h2>';
    $html .= '<p>Start Date: ' . $start_date . '</p>';
    $html .= '<p>End Date: ' . $end_date . '</p>';
    $html .= '<table border="1">';
    $html .= '<tr><th>Product Code</th><th>Product Name</th><th>Total Quantity Sold</th><th>Total Sales</th></tr>';

    // Add data rows
    foreach ($data as $row) {
        $html .= '<tr>';
        $html .= '<td>' . $row['ProductCode'] . '</td>';
        $html .= '<td>' . $row['ProductName'] . '</td>';
        $html .= '<td>' . $row['total_quantity'] . '</td>';
        $html .= '<td>' . $row['total_sales'] . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    ob_end_clean();
    $pdf->Output('product_sales_report.pdf', 'I');
    exit;
};
?>

<!doctype html>
<html lang="en">
<head>
    <title>Product Report</title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="Back_Styles/Adminpage.css">
</head>

<body>
    <header role="banner">
        <h1>Admin Panel</h1>
        <ul class="utilities">
            <br>
            <li class="users"><a href="#">My Account</a></li>
            <li class="logout warn"><a href="../logout.php">Log Out</a></li>
        </ul>
    </header>

    <main role="main">
        <section class="panel important">
            <h2>Product Sales Report</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required>
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" required>
                <input type="submit" value="Generate Report">
            </form>
<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($product_sales_data)) : ?>
                <h3>Product Sales</h3>
                <table>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Total Quantity Sold</th>
                        <th>Total Sales</th>
                    </tr>
                    <?php foreach ($product_sales_data as $row) : ?>
                        <tr>
                            <td><?php echo $row['ProductCode']; ?></td>
                            <td><?php echo $row['ProductName']; ?></td>
                            <td><?php echo $row['total_quantity']; ?></td>
                            <td><?php echo $row['total_sales']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
                    <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">
                    <input type="submit" name="generate_pdf" value="Generate PDF">
                </form>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>

<?php
// Check if the Generate PDF button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate_pdf']) && !empty($product_sales_data)) {
    generatePDF($product_sales_data, $start_date, $end_date);
}
?>
