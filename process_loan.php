<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$full_name = $_POST['full_name'] ?? '';
$amount = $_POST['amount'] ?? '';
$purpose = $_POST['purpose'] ?? '';
$repayment_period = $_POST['repayment_period'] ?? '';
$phone = $_POST['phone'] ?? '';

if (empty($amount) || empty($purpose) || empty($repayment_period) || empty($phone)) {
    echo "Please fill in all fields.";
    exit();
}

// Insert loan application
$stmt = $conn->prepare("INSERT INTO loan_applications (user_id, full_name, amount, purpose, repayment_period, phone, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
$stmt->bind_param("isdsss", $user_id, $full_name, $amount, $purpose, $repayment_period, $phone);

if ($stmt->execute()) {
    echo "<script>alert('Loan application submitted successfully.'); window.location.href='user-dashboard.php';</script>";
} else {
    echo "Something went wrong: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
