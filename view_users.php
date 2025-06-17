<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'sidu_portal';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, full_name, email, phone, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);

echo "<h2>Registered Users</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Full Name</th><th>Email</th><th>Phone</th><th>Registered At</th></tr>";

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['full_name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['created_at']}</td>
          </tr>";
  }
} else {
  echo "<tr><td colspan='5'>No users found.</td></tr>";
}
echo "</table>";

$conn->close();
?>
