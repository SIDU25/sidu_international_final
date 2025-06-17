<?php
session_start();

// Replace these with your actual admin credentials
$admin_username = 'admin';
$admin_password = 'sidu123';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin-dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Sidu International Ltd</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e6f2ff;
            margin: 0;
            padding: 0;
        }
        .login-box {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #005580;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #FFD700;
            color: #000;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #e6c200;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Admin Login</h2>
    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Enter admin username" required>
        <input type="password" name="password" placeholder="Enter admin password" required>
        <input type="submit" value="Login">
    </form>
</div>

</body>
</html>
