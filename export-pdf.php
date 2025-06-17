<?php
require_once __DIR__ . '/vendor/autoload.php';
include 'db.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    die("Access denied.");
}

if (!isset($_GET['id'])) {
    die("No loan ID provided.");
}

$loan_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM loan_applications WHERE id = ?");
$stmt->bind_param("i", $loan_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Loan not found.");
}

$data = $result->fetch_assoc();
$html = "<h2>Loan Application Details</h2><hr>";

foreach ($data as $key => $value) {
    $html .= "<p><strong>" . ucwords(str_replace("_", " ", $key)) . ":</strong> " . htmlspecialchars($value) . "</p>";
}

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output("Loan-Application-{$loan_id}.pdf", "D"); // Forces download
?>
