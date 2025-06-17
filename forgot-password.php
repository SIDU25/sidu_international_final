<?php
session_start();
include 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_number = trim($_POST['id_number'] ?? '');

    if (empty($id_number)) {
        $message = "Please enter your ID number.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE id_number = ?");
        $stmt->bind_param("s", $id_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $message = "No account found with that ID number.";
        } else {
            $user = $result->fetch_assoc();
            $token = bin2hex(random_bytes(16));
            $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour

            $update = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
            $update->bind_param("ssi", $token, $expires, $user['id']);
            $update->execute();

            $resetLink = "http://localhost/sidu_international/reset-password.php?token=$token";

            $message = "Password reset link generated.<br>";
            $message .= "Click below to reset your password:<br>";
            $message .= "<a href='$resetLink'>$resetLink</a>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Forgot Password - Sidu International</title>
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

<form method="post" action="">
  <h2>Forgot Password</h2>
  <input type="text" name="id_number" placeholder="Enter your ID Number" required />
  <button type="submit">Send Reset Link</button>
</form>

<?php if ($message): ?>
  <div class="message <?= strpos($message, 'No account') !== false || strpos($message, 'Please enter') !== false ? 'error' : '' ?>">
    <?= $message ?>
  </div>
<?php endif; ?>

</body>
</html>
