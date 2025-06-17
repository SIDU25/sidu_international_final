<?php
session_start();

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'sidu_portal';

$conn = new mysqli($host, $user, $pass, $db);

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_number = trim($_POST['id_number']);
    $password = trim($_POST['password']);

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE id_number = ?");
    $stmt->bind_param("s", $id_number);
    $stmt->execute();
    $stmt->store_result();

    // If user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Login success
            $_SESSION['user_id'] = $id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['id_number'] = $id_number;

            header("Location: user-dashboard.php"); // redirect to dashboard
            exit();
        } else {
            echo "<script>alert('Incorrect password'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('ID number not found'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>
