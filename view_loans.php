<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'sidu_portal';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM loan_applications ORDER BY submitted_at DESC";
$result = $conn->query($sql);

echo "<h2>Loan Applications</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr>
        <th>ID</th><th>Full Name</th><th>Email</th><th>Phone</th>
        <th>Loan Type</th><th>Amount</th><th>Purpose</th><th>Submitted</th>
      </tr>";

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['full_name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['loan_type']}</td>
            <td>{$row['amount']}</td>
            <td>{$row['purpose']}</td>
            <td>{$row['submitted_at']}</td>
          </tr>";
  }
} else {
  echo "<tr><td colspan='8'>No loan applications submitted yet.</td></tr>";
}
echo "</table>";

$conn->close();
?>
