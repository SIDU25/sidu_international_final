<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = new mysqli('localhost', 'root', '', 'sidu_portal');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT full_name, id_number, loan_amount, loan_status, repayment_period FROM loan_applications";
$result = $conn->query($sql);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'full_name');
$sheet->setCellValue('B1', 'id_number');
$sheet->setCellValue('C1', 'Loan Amount');
$sheet->setCellValue('D1', 'loan_status');
$sheet->setCellValue('E1', 'repayment_period');

$row = 2;
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['full_name']);
    $sheet->setCellValue('B' . $row, $data['id_number']);
    $sheet->setCellValue('C' . $row, $data['loan_amount']);
    $sheet->setCellValue('D' . $row, $data['loan_status']);
    $sheet->setCellValue('E' . $row, $data['repayment_period']);
    $row++;
}

$writer = new Xlsx($spreadsheet);
$filename = 'loan_data.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. $filename .'"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
;

