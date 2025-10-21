<?php
include("./config/config.php");

$format = $_GET['format'] ?? 'csv';
$birthday = $_GET['birthday'] ?? '';
$time = $_GET['time'] ?? '';

$where = "1=1";
if (!empty($birthday)) {
    $where .= " AND a.date = '$birthday'";
}
if (!empty($time)) {
    $where .= " AND a.sign_on >= '$time'";
}

$query = "SELECT e.employees_id, e.first_name, e.last_name, e.email, e.department, 
                 a.sign_on, a.sign_out, a.date
          FROM employees e
          INNER JOIN attendance a ON e.employees_id = a.employees_id
          WHERE $where
          ORDER BY a.date DESC";
$result = mysqli_query($conn, $query);

// EXPORT BASED ON FORMAT
if ($format == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=report.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Employee Name', 'Email', 'Department', 'Date', 'Sign-In', 'Sign-Out']);
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $row['employees_id'],
            $row['first_name'] . ' ' . $row['last_name'],
            $row['email'],
            $row['department'],
            $row['date'],
            $row['sign_on'],
            $row['sign_out']
        ]);
    }
    fclose($output);
    exit;
}
elseif ($format == 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=report.xls");
    echo "ID\tEmployee Name\tEmail\tDepartment\tDate\tSign-In\tSign-Out\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "{$row['employees_id']}\t{$row['first_name']} {$row['last_name']}\t{$row['email']}\t{$row['department']}\t{$row['date']}\t{$row['sign_on']}\t{$row['sign_out']}\n";
    }
    exit;
}
elseif ($format == 'pdf') {
    require('fpdf/fpdf.php');
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(190, 10, 'Attendance Report', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 10);
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(0, 8, "{$row['employees_id']} - {$row['first_name']} {$row['last_name']} - {$row['email']} - {$row['department']} - {$row['date']} - {$row['sign_on']} - {$row['sign_out']}", 0, 1);
    }
    $pdf->Output('D', 'report.pdf');
    exit;
}
?>
