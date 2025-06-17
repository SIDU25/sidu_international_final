<?php
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "sidu_portal";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$full_name = trim($_POST['full_name']);
$phone = trim($_POST['phone']);
$id_number = trim($_POST['id_number']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validate passwords
if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

// Check if user exists
$sql_check = "SELECT * FROM users WHERE id_number = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $id_number);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    die("A user with this ID number already exists.");
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert into database
$sql_insert = "INSERT INTO users (full_name, phone, id_number, password) VALUES (?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("ssss", $full_name, $phone, $id_number, $hashed_password);

if ($stmt_insert->execute()) {
    // Set session variables
    $_SESSION['user_name'] = $full_name;
    $_SESSION['id_number'] = $id_number;
    $_SESSION['phone'] = $phone;

    // Redirect to dashboard
    header("Location: dashboard.php");
    exit();
} else {
    echo "Error: " . $stmt_insert->error;
}

$conn->close();
?>
