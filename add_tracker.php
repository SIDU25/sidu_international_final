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
$id_number = isset($_POST['id_number']) ? trim($_POST['id_number']) : '';
$date = isset($_POST['date']) ? trim($_POST['date']) : '';
$amount_paid = isset($_POST['amount_paid']) ? trim($_POST['amount_paid']) : '';
$balance = isset($_POST['balance']) ? trim($_POST['balance']) : '';
$note = isset($_POST['note']) ? trim($_POST['note']) : '';

if (empty($id_number) || empty($date) || empty($amount_paid) || empty($balance)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

// Insert into database
$sql = "INSERT INTO loan_tracker (id_number, date, amount_paid, balance, note) 
        VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdds", $id_number, $date, $amount_paid, $balance, $note);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Tracker entry added successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add tracker entry.']);
}

$stmt->close();
$conn->close();
