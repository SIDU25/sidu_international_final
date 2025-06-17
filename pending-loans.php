<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

$result = $conn->query("SELECT id, full_name, phone, loan_amount, loan_status FROM loan_applications WHERE loan_status = 'Pending' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pending Loans - SIDU Admin</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f4f8fb; padding: 30px; }
    h2 { color: #004080; text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    th, td { border: 1px solid #ccc; padding: 12px; text-align: center; font-size: 14px; }
    th { background-color: #004080; color: #fff; }
    tr:nth-child(even) { background-color: #f1f5f9; }
    .actions a {
      margin: 0 5px;
      padding: 6px 12px;
      text-decoration: none;
      color: #fff;
      border-radius: 4px;
      font-size: 13px;
    }
    .approve { background-color: green; }
    .deny { background-color: crimson; }
    .view { background-color: #004080; }
  </style>
</head>
<body>

<h2>Pending Loan Applications</h2>

<table>
  <tr>
    <th>#</th>
    <th>Full Name</th>
    <th>Phone</th>
    <th>Amount Requested</th>
    <th>Actions</th>
  </tr>
  <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= htmlspecialchars($row['full_name']) ?></td>
      <td><?= htmlspecialchars($row['phone']) ?></td>
      <td>Ksh <?= number_format($row['loan_amount']) ?></td>
      <td class="actions">
        <a href="loan-status.php" class="approve">Approve</a>
        <a href="loan-status.php" class="deny">Deny</a>
        <a href="print-application.php?id=<?= $row['id'] ?>" target="_blank" class="view">View</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

</body>
</html>
