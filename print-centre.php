<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

$applications = $conn->query("SELECT id, full_name, loan_amount, loan_status FROM loan_applications ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Print Center - SIDU Admin</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; padding: 30px; background: #f0f4f9; }
    h2 { text-align: center; color: #004080; }
    table { width: 100%; margin-top: 20px; border-collapse: collapse; background: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: center; font-size: 14px; }
    th { background: #004080; color: #fff; }
    tr:nth-child(even) { background: #f9f9f9; }
    .actions a {
      padding: 6px 12px;
      margin: 2px;
      border-radius: 4px;
      text-decoration: none;
      font-size: 13px;
      color: white;
    }
    .pdf { background: darkred; }
    .excel { background: green; }
    .word { background: #004080; }
    .view { background: orange; }
  </style>
</head>
<body>

<h2>Print & Export Center</h2>

<table>
  <tr>
    <th>#</th>
    <th>Full Name</th>
    <th>Loan Amount</th>
    <th>Status</th>
    <th>Export Options</th>
  </tr>
  <?php $i = 1; while ($row = $applications->fetch_assoc()): ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= htmlspecialchars($row['full_name']) ?></td>
      <td>Ksh <?= number_format($row['loan_amount']) ?></td>
      <td><?= $row['loan_status'] ?></td>
      <td class="actions">
        <a href="print-application.php?id=<?= $row['id'] ?>" target="_blank" class="pdf">PDF</a>
        <a href="export-word.php?id=<?= $row['id'] ?>" class="word">Word</a>
        <a href="export-excel.php?id=<?= $row['id'] ?>" class="excel">Excel</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

</body>
</html>
