<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "sidu_portal";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM loan_applications ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin - View Loan Applications</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 8px; }
    th { background-color: skyblue; color: black; }
    h2 { color: goldenrod; }
  </style>
</head>
<body>

<h2>Submitted Loan Applications</h2>
<table>
  <tr>
    <th>ID</th>
    <th>Full Name</th>
    <th>ID Number</th>
    <th>Phone</th>
    <th>Loan Amount</th>
    <th>Purpose</th>
    <th>Submitted</th>
  </tr>

  <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= htmlspecialchars($row['full_name']) ?></td>
      <td><?= $row['id_number'] ?></td>
      <td><?= $row['phone'] ?></td>
      <td>Ksh <?= number_format($row['loan_amount']) ?></td>
      <td><?= htmlspecialchars($row['loan_purpose']) ?></td>
      <td><?= $row['created_at'] ?></td>
    </tr>
  <?php endwhile; ?>

</table>

</body>
</html>
