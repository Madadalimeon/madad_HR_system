<?php
require 'vendor/autoload.php';
include("./config/config.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$where = "1=1";
$dayset = $_GET['start_date'] ?? '';
$dayend = $_GET['end_date'] ?? '';
$employee_id = $_GET['employee_id'] ?? '';

if (!empty($dayset) && !empty($dayend)) {
    $where .= " AND a.date BETWEEN '$dayset' AND '$dayend'";
} elseif (!empty($dayset)) {
    $where .= " AND a.date = '$dayset'";
}
if (!empty($employee_id)) {
    $where .= " AND e.employees_id = '$employee_id'";
}

$Report = "SELECT e.employees_id, e.first_name, e.last_name, e.email, e.department, 
           a.sign_on, a.sign_out, a.date 
           FROM employees e  
           INNER JOIN attendance a ON a.employees_id = e.employees_id 
           WHERE $where
           ORDER BY a.date DESC";

$Report_Generation = mysqli_query($conn, $Report);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Employee ID');
$sheet->setCellValue('B1', 'First Name');
$sheet->setCellValue('C1', 'Last Name');
$sheet->setCellValue('D1', 'Email');
$sheet->setCellValue('E1', 'Department');
$sheet->setCellValue('F1', 'Sign On');
$sheet->setCellValue('G1', 'Sign Out');
$sheet->setCellValue('H1', 'Date');

$rowNumber = 2;
if (mysqli_num_rows($Report_Generation) > 0) {
    while ($row = mysqli_fetch_assoc($Report_Generation)) {
        $sheet->setCellValue('A' . $rowNumber, $row['employees_id']);
        $sheet->setCellValue('B' . $rowNumber, $row['first_name']);
        $sheet->setCellValue('C' . $rowNumber, $row['last_name']);
        $sheet->setCellValue('D' . $rowNumber, $row['email']);
        $sheet->setCellValue('E' . $rowNumber, $row['department']);
        $sheet->setCellValue('F' . $rowNumber, $row['sign_on']);
        $sheet->setCellValue('G' . $rowNumber, $row['sign_out']);
        $sheet->setCellValue('H' . $rowNumber, $row['date']);
        $rowNumber++;
    }
}

$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="attendance_report.xlsx"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
