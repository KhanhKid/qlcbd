<?php

// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");

require('tfpdf.php');

$pdf = new tFPDF();
$pdf->AddPage();

// Add a Unicode font (uses UTF-8)
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

$pdf->SetFont('DejaVu','',12);
$pdf->Text(5, 10, 'Bộ, Tỉnh: TP. HỒ CHÍ MINH');
$pdf->Text(5, 16, 'Đơn vị trực thuộc: THÀNH ĐOÀN');
$pdf->Text(5, 22, 'Đơn vị cơ sở: .......');


$pdf->SetFont('DejaVu','',24);
$text = 'SƠ YẾU LÝ LỊCH';
$pdf->Text($pdf->DefPageSize[0] / 2 - $pdf->GetStringWidth($text) / 2, 40, $text);

$pdf->SetFont('DejaVu','',12);
$text = 'A123-432';
$pdf->Text($pdf->DefPageSize[0] - $pdf->GetStringWidth($text) - 5, 48, $text);
$text = 'Số hiệu cán bộ, công chức';
$pdf->Text($pdf->DefPageSize[0] - $pdf->GetStringWidth($text) - 5, 52, $text);


$pdf->Image('http://localhost/qlcbd/vendor/tfpdf/1.jpg', 10, 55, 40, 55);

$pdf->SetX(500);

// Select a standard font (uses windows-1252)
$pdf->SetFont('Arial','',14);
$pdf->Ln(10);

$pdf->Output();
?>
