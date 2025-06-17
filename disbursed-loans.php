<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

$result = $conn->query("SELECT id, full_name, phone, loan_amount, loan_given_date FROM loan_applications WHERE loan_status = 'Disbursed' ORDER BY loan_given_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Disbursed Loans - SIDU Admin</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f0f4f9; padding: 30px; }
    h2 { color: #004080; text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    th, td { padding: 12px; border: 1px solid #ccc; text-align: center; font-size: 14px; }
    th { background-color: #004080; color: white; }
    tr:nth-child(even) { background-color: #f9f9f9; }
    .view-btn { background: #004080; color: white; padding: 6px 10px; border: none; border-radius: 4px; text-decoration: none; font-size: 13px; }
    .view-btn:hover { background: #002f5f; }
  </style>
</head>
<body>

<h2>Disbursed Loans List</h2>

<table>
  <tr>
    <th>#</th>
    <th>Full Name</th>
    <th>Phone</th>
    <th>Loan Amount</th>
    <th>Date Disbursed</th>
    <th>Action</th>
  </tr>
  <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= htmlspecialchars($row['full_name']) ?></td>
      <td><?= htmlspecialchars($row['phone']) ?></td>
      <td>Ksh <?= number_format($row['loan_amount']) ?></td>
      <td><?= date("d M Y", strtotime($row['loan_given_date'])) ?></td>
      <td>
        <a href="print-application.php?id=<?= $row['id'] ?>" class="view-btn" target="_blank">View Application</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

</body>
</html>
