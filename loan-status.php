<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $app_id = intval($_POST['application_id']);
    $new_status = $_POST['loan_status'];
    $stmt = $conn->prepare("UPDATE loan_applications SET loan_status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $app_id);
    $stmt->execute();
    $stmt->close();
}

$results = $conn->query("SELECT id, full_name, id_number, phone, loan_amount, loan_status FROM loan_applications ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Loan Status Manager - SIDU Admin</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f4f4f9; padding: 20px; }
    h2 { text-align: center; color: #004080; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    th, td { padding: 12px; border: 1px solid #ccc; text-align: center; font-size: 14px; }
    th { background-color: #004080; color: white; }
    tr:nth-child(even) { background-color: #f9f9f9; }
    select, button { padding: 6px 10px; font-size: 14px; }
    .status-badge { padding: 4px 8px; border-radius: 5px; font-weight: bold; }
    .Pending { background: orange; color: white; }
    .Approved { background: green; color: white; }
    .Denied { background: crimson; color: white; }
    .Disbursed { background: blue; color: white; }
  </style>
</head>
<body>

<h2>Loan Status Management</h2>

<table>
  <tr>
    <th>#</th>
    <th>Name</th>
    <th>ID Number</th>
    <th>Phone</th>
    <th>Amount</th>
    <th>Current Status</th>
    <th>Change Status</th>
  </tr>
  <?php if ($results->num_rows > 0): $count = 1; while ($row = $results->fetch_assoc()): ?>
    <tr>
      <td><?= $count++ ?></td>
      <td><?= htmlspecialchars($row['full_name']) ?></td>
      <td><?= htmlspecialchars($row['id_number']) ?></td>
      <td><?= htmlspecialchars($row['phone']) ?></td>
      <td>Ksh <?= number_format($row['loan_amount']) ?></td>
      <td><span class="status-badge <?= $row['loan_status'] ?>"><?= $row['loan_status'] ?></span></td>
      <td>
        <form method="POST" style="display:inline-flex; gap:5px; align-items:center;">
          <input type="hidden" name="application_id" value="<?= $row['id'] ?>">
          <select name="loan_status">
            <option <?= $row['loan_status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option <?= $row['loan_status'] === 'Approved' ? 'selected' : '' ?>>Approved</option>
            <option <?= $row['loan_status'] === 'Denied' ? 'selected' : '' ?>>Denied</option>
            <option <?= $row['loan_status'] === 'Disbursed' ? 'selected' : '' ?>>Disbursed</option>
          </select>
          <button type="submit" name="update_status">Update</button>
        </form>
      </td>
    </tr>
  <?php endwhile; else: ?>
    <tr><td colspan="7">No applications found.</td></tr>
  <?php endif; ?>
</table>

</body>
</html>
