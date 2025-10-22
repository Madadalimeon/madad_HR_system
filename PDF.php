<?php
require('./fpdf/fpdf.php');

class myPDF extends FPDF {
    function Header() {
        $this->SetFont('Times','B',14);
        $this->SetTextColor(0,0,255);
        $this->Cell(0,5,'HOW TO GENERATE PDF USING FPDF',0,1,'C');
        $this->Ln(5);
        $this->SetTextColor(0,0,0);
        $this->SetFont('Times','B',12);
        $this->Cell(0,10,'FPDF DOCUMENTATION',0,1,'C');
        $this->Ln(5);
    }
    function Footer() {
        $this->SetY(-25);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page ' . $this->PageNo() . '/{nb}',0,0,'C');
    }
    function headerAttributes() {
        $this->SetFont('Times','B',10);
        $this->SetFillColor(200,220,255);
        $this->Cell(30,10,'Attributes',1,0,'C',true);
        $this->Cell(45,10,'Description',1,0,'C',true);
        $this->Cell(60,10,'How to Use',1,0,'C',true);
        $this->Cell(40,10,'Tutorials',1,0,'C',true);
        $this->Ln();
    }
    function addRow($attr, $desc, $use, $tutorial) {
        $this->SetFont('Times','',12);
        $this->Cell(30,10,$attr,1,0,'C');
        $this->Cell(45,10,$desc,1,0,'C');
        $this->Cell(60,10,$use,1,0,'C');
        $this->Cell(40,10,$tutorial,1,0,'C');
        $this->Ln();
    }
}
$pdf = new myPDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->headerAttributes();
$pdf->addRow('Color', 'Specifies text color', 'SetTextColor(r,g,b)', 'FPDF Manual');
$pdf->addRow('Font', 'Specifies font type and size', 'SetFont(family,style,size)', 'FPDF Tutorial');
$pdf->addRow('Cell', 'Outputs a cell', 'Cell(width,height,text,border,ln,align)', 'FPDF Docs');
$pdf->addRow('Ln', 'Line break', 'Ln(height)', 'FPDF Manual');
$pdf->addRow('AddPage', 'Adds new page', 'AddPage()', 'FPDF Tutorial');
$pdf->Output();
?>
