<?php
ob_start();
include "../db_config.php";
include "sidenav.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $daily_query = "SELECT DATE(OrderDate) as date, SUM(OrderTotal) as total_amount FROM OrderHeader WHERE OrderDate BETWEEN '$start_date' AND '$end_date' GROUP BY DATE(OrderDate)";
    $weekly_query = "SELECT YEAR(OrderDate) AS year, WEEK(OrderDate) AS week, SUM(OrderTotal) as total_amount FROM OrderHeader WHERE OrderDate BETWEEN '$start_date' AND '$end_date' GROUP BY YEAR(OrderDate), WEEK(OrderDate)";
    $monthly_query = "SELECT YEAR(OrderDate) AS year, MONTH(OrderDate) AS month, SUM(OrderTotal) as total_amount FROM OrderHeader WHERE OrderDate BETWEEN '$start_date' AND '$end_date' GROUP BY YEAR(OrderDate), MONTH(OrderDate)";
    $daily_result = mysqli_query($conn, $daily_query);
    $weekly_result = mysqli_query($conn, $weekly_query);
    $monthly_result = mysqli_query($conn, $monthly_query);
    
}

function generatePDF($daily_result, $weekly_result, $monthly_result, $start_date, $end_date) {
    // Include TCPDF library
    include ('vendor/TCPDF-main/tcpdf.php');
    // Create new TCPDF instance
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle('Income Summary Report');
    $pdf->SetSubject('Income Summary Report');
    $pdf->SetKeywords('TCPDF, PDF, Income, Summary, Report');

    $pdf->AddPage();
    $pdf->SetFont('thsarabun', '', 12);

    $html = '<div style="text-align: center;">';
    $html .= '<h3>บริษัท ตัวอย่าง จํากัด</h3>';
    $html .= '<h3>รายงานสรุปยอดขาย</h3>';
    $html .= '<p>ช่วงวันที่ ' . $start_date . ' ถึง ' . $end_date . '</p>';
    $html .= '</div>';
    $html .= '<p>พิมพ์วันที่ : ' . date("Y-m-d H:i:s") . '</p>';

    //Day
    $html .= '<h3>รายงานสรุปยอดขายประจำวัน</h3>';
    $html .= '<table border="1">';
    $html .= '<tr><th>วันที่</th><th>ยอดขายสุทธิ</th></tr>';
    while ($row = mysqli_fetch_assoc($daily_result)) {
        $html .= '<tr>';
        $html .= '<td>' . $row['date'] . '</td>';
        $html .= '<td>' . $row['total_amount'] . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';
    //Week
    $html .= '<h3>รายงายสรุปยอดขายประจำสัปดาห์</h3>';
    $html .= '<table border="1">';
    $html .= '<tr><th>สัปดาห์</th><th>ยอดขายสุทธิ</th></tr>';
    while ($row = mysqli_fetch_assoc($weekly_result)) {
        $html .= '<tr>';
        $html .= '<td>' . $row['week'] . '</td>';
        $html .= '<td>' . $row['total_amount'] . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';
    //Month
    $html .= '<h3>รายงายสรุปยอดขายประจำเดือน</h3>';
    $html .= '<table border="1">';
    $html .= '<tr><th>เดือน</th><th>ยอดขายสุทธิ</th></tr>';
    while ($row = mysqli_fetch_assoc($monthly_result)) {
        $html .= '<tr>';
        $html .= '<td>' . $row['month'] . '</td>';
        $html .= '<td>' . $row['total_amount'] . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    ob_end_clean();
    $pdf->Output('income_summary_report.pdf', 'I');
    exit;
}
?>
<!-- Check if the Generate PDF button is clicked -->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate_pdf'])) {
    generatePDF($daily_result, $weekly_result, $monthly_result, $start_date, $end_date);
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Income Summary Report</title>
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
            <h2>Income Summary Report</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required>
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" required>
                <input type="submit" name="generate_report" value="Generate Report">
                <input type="submit" name="generate_pdf" value="Generate PDF">
            </form>

            <?php if ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
                <h3>Daily Summary</h3>
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Total Amount</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($daily_result)) : ?>
                        <tr>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php echo $row['total_amount']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>

                <h3>Weekly Summary</h3>
                <table>
                    <tr>
                        <th>Year</th>
                        <th>Week</th>
                        <th>Total Amount</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($weekly_result)) : ?>
                        <tr>
                            <td><?php echo $row['year']; ?></td>
                            <td><?php echo $row['week']; ?></td>
                            <td><?php echo $row['total_amount']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>

                <h3>Monthly Summary</h3>
                <table>
                    <tr>
                        <th>Year</th>
                        <th>Month</th>
                        <th>Total Amount</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($monthly_result)) : ?>
                        <tr>
                            <td><?php echo $row['year']; ?></td>
                            <td><?php echo $row['month']; ?></td>
                            <td><?php echo $row['total_amount']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="submit" name="generate_pdf" value="Generate PDF">
                </form>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>

