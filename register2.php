<?php
//register.php

$host = "localhost";
$username = "root";
$password = "";
$database = "sidu_portal";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Receive form inputs
$full_name = trim($_POST['full_name']);
$phone = trim($_POST['phone']);
$id_number = trim($_POST['id_number']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Basic validation
if ($password !== $confirm_password) {
    echo "<script>alert('❌ Error: Passwords do not match.'); window.history.back();</script>";
    exit();
}

// Validate ID number (8-digit national ID or passport format)
if (!preg_match("/^(\d{8}|[A-Z]{1,2}\d{6,8})$/", $id_number)) {
    echo "<script>alert('❌ Error: Please enter a valid 8-digit National ID or Passport number (e.g., 12345678 or A1234567).'); window.history.back();</script>";
    exit();
}

// Validate phone number (Kenyan format)
if (!preg_match("/^(07|01|\+254)[0-9]{8,9}$/", $phone)) {
    echo "<script>alert('❌ Error: Please enter a valid phone number (e.g., 0712345678 or +254712345678).'); window.history.back();</script>";
    exit();
}

// Check if ID already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE id_number = ?");
$stmt->bind_param("s", $id_number);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>alert('❌ Error: This ID number is already registered. Please login instead.'); window.location.href='login.html';</script>";
    exit();
}
$stmt->close();

// Check if phone already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
$stmt->bind_param("s", $phone);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>alert('❌ Error: This phone number is already registered.'); window.history.back();</script>";
    exit();
}
$stmt->close();

// Hash password securely
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert user into database
$insert = $conn->prepare("INSERT INTO users (full_name, phone, id_number, password, created_at) VALUES (?, ?, ?, ?, NOW())");
$insert->bind_param("ssss", $full_name, $phone, $id_number, $hashed_password);

if ($insert->execute()) {
    echo "<script>
        alert('✅ Registration successful! You can now login with your ID number.');
        window.location.href='login.html';
    </script>";
} else {
    echo "<script>alert('❌ Error: Registration failed. Please try again.'); window.history.back();</script>";
}

$insert->close();
$conn->close();
?>