<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Change if needed
$dbname = "sidu_portal"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed.']));
}

// Validate inputs
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$date = isset($_POST['date']) ? trim($_POST['date']) : '';
$amount_paid = isset($_POST['amount_paid']) ? trim($_POST['amount_paid']) : '';
$balance = isset($_POST['balance']) ? trim($_POST['balance']) : '';
$note = isset($_POST['note']) ? trim($_POST['note']) : '';

if ($id <= 0 || empty($date) || empty($amount_paid) || empty($balance)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

// Update entry
$sql = "UPDATE loan_tracker SET date = ?, amount_paid = ?, balance = ?, note = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sddsi", $date, $amount_paid, $balance, $note, $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Tracker entry updated successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update tracker entry.']);
}

$stmt->close();
$conn->close();
