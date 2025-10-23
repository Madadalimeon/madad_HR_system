<?php
include("./config/config.php");
$where = "1=1";
$e_name = "SELECT employees_id, first_name, last_name FROM employees";
$employees_result = mysqli_query($conn, $e_name);

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
require('./fpdf/fpdf.php');
class myPDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Times', 'B', 14);
        $this->SetTextColor(0, 0, 255);
        $this->Cell(0, 5, 'HR_System', 0, 1, 'C');
        $this->Ln(5);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Times', 'B', 12);
        $this->Cell(0, 10, 'attendance Report', 0, 1, 'C');
        $this->Ln(5);
    }
    function Footer()
    {
        $this->SetY(-25);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
    function headerAttributes()
    {
        $this->SetFont('Times', 'B', 10);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(20, 10, 'ID', 1, 0, 'C', true);
        $this->Cell(40, 10, 'Employee Name', 1, 0, 'C', true);
        $this->Cell(60, 10, 'Email', 1, 0, 'C', true);
        $this->Cell(25, 10, 'Date', 1, 0, 'C', true);
        $this->Cell(25, 10, 'Sign-in', 1, 0, 'C', true);
        $this->Cell(25, 10, 'Sign-out', 1, 0, 'C', true);
        $this->Ln();
    }
    function addRow($id, $name, $email, $date, $signIn, $signOut)
    {
        $this->SetFont('Times', '', 12);
        $this->Cell(20, 10, $id, 1, 0, 'C');
        $this->Cell(40, 10, $name, 1, 0, 'C');
        $this->Cell(60, 10, $email, 1, 0, 'C');
        $this->Cell(25, 10, $date, 1, 0, 'C');
        $this->Cell(25, 10, $signIn, 1, 0, 'C');
        $this->Cell(25, 10, $signOut, 1, 0, 'C');
        $this->Ln();
    }
}
$pdf = new myPDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->headerAttributes();
if (mysqli_num_rows($Report_Generation) > 0) {
    while ($row = mysqli_fetch_assoc($Report_Generation)) {
        $name = $row['first_name'] . ' ' . $row['last_name'];
        $pdf->addRow(
            $row['employees_id'],
            $name,
            $row['email'],
            $row['date'],
            $row['sign_on'],
            $row['sign_out']
        );
    }
}
$pdf->Output();



?>