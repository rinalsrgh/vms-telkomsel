<?php
require('fpdf/fpdf.php');

try {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Hello World!', 0, 1, 'C');
    $pdf->Output();
} catch (Exception $e) {
    echo 'Error: ', $e->getMessage();
}
?>
