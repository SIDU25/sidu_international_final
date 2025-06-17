<?php
// save-tracker.php

$host = "localhost";
$user = "root";
$pass = "";
$db = "sidu_portal";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get application ID from the hidden input
$application_id = intval($_POST['application_id']);

// Loop through the submitted tracker entries
foreach ($_POST['payment_date'] as $index => $date) {
    $payment_date = $_POST['payment_date'][$index];
    $amount_paid = $_POST['amount_paid'][$index];
    $fine = $_POST['fine'][$index];
    $ref_no = $_POST['ref_no'][$index];
    $balance = $_POST['balance'][$index];
    $sidu_sign = $_POST['sidu_sign'][$index];

    // Only save if payment_date and amount_paid are filled
    if (!empty($payment_date) && !empty($amount_paid)) {
        $stmt = $conn->prepare("
            INSERT INTO loan_tracker 
            (application_id, payment_date, amount_paid, fine, ref_no, balance, sidu_sign)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "isdssds", 
            $application_id, 
            $payment_date, 
            $amount_paid, 
            $fine, 
            $ref_no, 
            $balance, 
            $sidu_sign
        );
        $stmt->execute();
        $stmt->close();
    }
}

// Redirect back to the admin view page
header("Location: print-application.php?id=" . $application_id);
exit();
?>
