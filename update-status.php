<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['action'])) {
    $id = intval($_POST['id']);
    $status = ($_POST['action'] === 'approve') ? 'Approved' : 'Pending';

    $stmt = $conn->prepare("UPDATE loan_applications SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        header("Location: loan-details.php?id=" . $id);
        exit();
    } else {
        echo "Failed to update status.";
    }
}
?>
