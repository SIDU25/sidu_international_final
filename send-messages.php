<?php
session_start();

// (Optional) Restrict access to admins only
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../login.html");
    exit();
}

// DB connection
$host = "localhost";
$dbname = "sidu_portal";
$username = " ";
$password = "";
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle message sending
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['message'])) {
    $recipients = $_POST['recipients'] ?? [];
    $message = trim($_POST['message']);

    if (!empty($recipients) && !empty($message)) {
        foreach ($recipients as $id_number) {
            // Optional: insert into database
            $stmt = $conn->prepare("INSERT INTO messages (id_number, message, sent_by) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $id_number, $message, $_SESSION['user_name']);
            $stmt->execute();

            // Optional: Integrate with an SMS API here
            // $stmt_phone = $conn->prepare("SELECT phone FROM users WHERE id_number = ?");
            // $stmt_phone->bind_param("s", $id_number);
            // $stmt_phone->execute();
            // $result = $stmt_phone->get_result();
            // if ($row = $result->fetch_assoc()) {
            //     $phone = $row['phone'];
            //     // Call your SMS API to send $message to $phone
            // }
        }
        $status = "✅ Message sent to selected users.";
    } else {
        $status = "⚠️ Please select at least one user and enter a message.";
    }
}

// Fetch user list
$users = [];
$result = $conn->query("SELECT full_name, id_number FROM users ORDER BY full_name ASC");
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Messages - Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            padding: 40px;
        }
        h2 {
            color: #333;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 12px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        textarea {
            width: 100%;
            height: 120px;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .users-list {
            max-height: 200px;
            overflow-y: auto;
            margin-top: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            background: #f1f1f1;
            border-radius: 6px;
        }
        .users-list label {
            font-weight: normal;
            display: block;
            margin-bottom: 5px;
        }
        button {
            margin-top: 20px;
            padding: 10px 25px;
            background: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .status {
            margin: 20px auto;
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>📨 Send Message to Users</h2>

    <?php if (isset($status)) echo "<div class='status'>$status</div>"; ?>

    <form method="POST" action="">
        <label>Message:</label>
        <textarea name="message" placeholder="Type your message here..." required></textarea>

        <label>Select Recipients:</label>
        <div class="users-list">
            <?php foreach ($users as $user): ?>
                <label>
                    <input type="checkbox" name="recipients[]" value="<?= $user['id_number']; ?>">
                    <?= htmlspecialchars($user['full_name']); ?> (<?= $user['id_number']; ?>)
                </label>
            <?php endforeach; ?>
        </div>

        <button type="submit">📤 Send Message</button>
    </form>
</body>
</html>
