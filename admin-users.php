<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.html");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "sidu_portal");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all registered users
$sql = "SELECT id, full_name, email, phone, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Registered Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef5fa;
            padding: 20px;
        }
        h2 {
            color: #004d80;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #007acc;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f9ff;
        }
        a {
            text-decoration: none;
            color: #004d80;
        }
    </style>
</head>
<body>
    <h2>Registered Users</h2>
    <a href="admin-dashboard.php">← Back to Dashboard</a>
    <br><br>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Registered On</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No users have registered yet.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
</html>
