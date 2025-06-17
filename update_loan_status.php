<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loan_id = $_POST["loan_id"];
    $status = $_POST["status"];
    $due_date = $_POST["due_date"];

    $conn = new mysqli("localhost", "root", "", "sidu_portal");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE loan_applications SET status = ?, due_date = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $status, $due_date, $loan_id);

    if ($stmt->execute()) {
        header("Location: admin-dashboard.php?success=1");
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: admin-dashboard.php");
}
?>
