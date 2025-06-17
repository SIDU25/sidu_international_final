<?php
include 'db2.php';

$token = $_GET['token'] ?? '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    if (empty($new_password)) {
        $message = "Please enter a new password.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
            $update->bind_param("si", $hashed_password, $user['id']);
            $update->execute();

            $message = "Password has been reset successfully. You can now <a href='login.php'>Login</a>.";
        } else {
            $message = "Invalid or expired reset token.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Reset Password - Sidu International</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f0f9ff; padding: 40px; }
    form { max-width: 400px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px #bbb; }
    h2 { text-align: center; color: #007BFF; }
    input, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; }
    button { background: skyblue; color: white; border: none; cursor: pointer; }
    .message { margin: 20px auto; padding: 15px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; color: #155724; text-align: center; }
    .error { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }
  </style>
</head>
<body>

<form method="post">
  <h2>Reset Password</h2>
  <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
  <input type="password" name="new_password" placeholder="Enter new password" required />
  <button type="submit">Reset Password</button>
</form>

<?php if ($message): ?>
  <div class="message <?= strpos($message, 'Invalid') !== false || strpos($message, 'Please') !== false ? 'error' : '' ?>">
    <?= $message ?>
  </div>
<?php endif; ?>

</body>
</html>
