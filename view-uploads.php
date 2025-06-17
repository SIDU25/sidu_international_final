<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

$result = $conn->query("SELECT id, full_name, passport_photo, id_front, id_back, collateral_doc FROM loan_applications ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Uploaded Documents - SIDU Admin</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f9f9f9; padding: 20px; }
    h2 { color: #004080; text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: center; font-size: 13px; }
    th { background-color: #004080; color: white; }
    img { height: 60px; border-radius: 4px; border: 1px solid #aaa; }
    a.pdf-link { color: navy; font-weight: bold; text-decoration: underline; }
    tr:nth-child(even) { background-color: #f1f5f9; }
  </style>
</head>
<body>

<h2>View All Uploaded Documents</h2>

<table>
  <tr>
    <th>#</th>
    <th>Name</th>
    <th>Passport</th>
    <th>ID Front</th>
    <th>ID Back</th>
    <th>Collateral</th>
  </tr>
  <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= htmlspecialchars($row['full_name']) ?></td>
      <td>
        <?php if ($row['passport_photo']): ?>
          <a href="<?= $row['passport_photo'] ?>" target="_blank">
            <img src="<?= $row['passport_photo'] ?>" alt="Passport">
          </a>
        <?php endif; ?>
      </td>
      <td>
        <?php if ($row['id_front']): ?>
          <a href="<?= $row['id_front'] ?>" target="_blank">
            <img src="<?= $row['id_front'] ?>" alt="ID Front">
          </a>
        <?php endif; ?>
      </td>
      <td>
        <?php if ($row['id_back']): ?>
          <a href="<?= $row['id_back'] ?>" target="_blank">
            <img src="<?= $row['id_back'] ?>" alt="ID Back">
          </a>
        <?php endif; ?>
      </td>
      <td>
        <?php if ($row['collateral_doc']): ?>
          <?php if (strtolower(pathinfo($row['collateral_doc'], PATHINFO_EXTENSION)) === 'pdf'): ?>
            <a href="<?= $row['collateral_doc'] ?>" target="_blank" class="pdf-link">View PDF</a>
          <?php else: ?>
            <a href="<?= $row['collateral_doc'] ?>" target="_blank">
              <img src="<?= $row['collateral_doc'] ?>" alt="Collateral">
            </a>
          <?php endif; ?>
        <?php endif; ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

</body>
</html>
