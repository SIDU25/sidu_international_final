<?php
session_start();
include 'db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    die("Please fill in both email and password.");
}

$query = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        header("Location: user-dashboard.php");
        exit;
    } else {
        echo "Incorrect password.";
    }
} else {
    echo "No user found with that email.";
}
?>
