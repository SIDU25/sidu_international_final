<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

$sql = "SELECT la.id, la.full_name, la.phone, la.loan_amount,
        IFNULL(SUM(lt.amount_paid), 0) AS total_paid,
        (la.loan_amount - IFNULL(SUM(lt.amount_paid), 0)) AS balance
        FROM loan_applications la
        LEFT JOIN loan_tracker lt ON la.id = lt.application_id
        GROUP BY la.id
        ORDER BY la.id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Loan Statements - SIDU Admin</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; padding: 30px; }
    h2 { color: #004080; text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    th, td { padding: 12px; border: 1px solid #ccc; text-align: center; font-size: 14px; }
    th { background-color: #004080; color: white; }
    tr:nth-child(even) { background-color: #f9f9f9; }
    .status-paid { background: green; color: white; padding: 5px 10px; border-radius: 4px; }
    .status-pending { background: orange; color: white; padding: 5px 10px; border-radius: 4px; }
  </style>
</head>
<body>

<h2>Loan Statements Summary</h2>

<table>
  <tr>
    <th>#</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Loan Amount</th>
    <th>Total Paid</th>
    <th>Balance</th>
    <th>Status</th>
    <th>Action</th>
  </tr>
  <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= htmlspecialchars($row['full_name']) ?></td>
      <td><?= htmlspecialchars($row['phone']) ?></td>
      <td>Ksh <?= number_format($row['loan_amount']) ?></td>
      <td>Ksh <?= number_format($row['total_paid']) ?></td>
      <td>Ksh <?= number_format($row['balance']) ?></td>
      <td>
        <?php if ($row['balance'] <= 0): ?>
          <span class="status-paid">Fully Paid</span>
        <?php else: ?>
          <span class="status-pending">Pending</span>
        <?php endif; ?>
      </td>
      <td><a href="print-application.php?id=<?= $row['id'] ?>" target="_blank">View Application</a></td>
    </tr>
  <?php endwhile; ?>
</table>

</body>
</html>
