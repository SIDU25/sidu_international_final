<?php
// db.php should already be included with your database connection
include 'db.php';

// Get form data
$full_name = $_POST['full_name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Basic validation
if (empty($full_name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
    echo "Please fill all required fields.";
    exit;
}

if ($password !== $confirm_password) {
    echo "Passwords do not match.";
    exit;
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if email exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Email is already registered.";
    exit;
}
$stmt->close();

// Insert user
$stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $full_name, $email, $phone, $hashed_password);

if ($stmt->execute()) {
    echo "Registration successful. You can now <a href='login.html'>log in</a>.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
