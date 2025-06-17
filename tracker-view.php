<?php
$mysqli = new mysqli("localhost", "root", "", "sidu_portal"); // adjust DB if needed

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query("SELECT * FROM loan_tracker ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Loan Repayment Tracker Records</title>
  <style>
    body {
      font-family: "Times New Roman", serif;
      font-size: 11pt;
      background-color: #f0f8ff;
      padding: 20px;
    }

    h2 {
      text-align: center;
      color: #045eab;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      margin-top: 20px;
      background: #fff;
    }

    th, td {
      border: 1px solid #aaa;
      padding: 6px;
      text-align: left;
      font-size: 10pt;
    }

    th {
      background-color: goldenrod;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .btn-print {
      margin: 10px 0;
      padding: 6px 12px;
      background-color: #045eab;
      color: white;
      border: none;
      cursor: pointer;
    }

    @media print {
      .btn-print {
        display: none;
      }
    }
  </style>
</head>
<body>

<h2>SIDU Loan Tracker Records</h2>

<button onclick="window.print()" class="btn-print">Print This Page</button>

<table>
  <thead>
    <tr>
      <th>Client</th>
      <th>ID</th>
      <th>Phone</th>
      <th>Loaned Amount</th>
      <th>Daily Payment</th>
      <th>Agreement Date</th>
      <th>Repayments (Latest 3)</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()) : ?>
    <tr>
      <td><?= htmlspecialchars($row['client_name']) ?></td>
      <td><?= htmlspecialchars($row['id_number']) ?></td>
      <td><?= htmlspecialchars($row['phone']) ?></td>
      <td>Ksh <?= number_format($row['loaned_amount']) ?></td>
      <td>Ksh <?= number_format($row['daily_payment']) ?></td>
      <td><?= $row['agreement_date'] ?></td>
      <td>
        <?= $row['date1'] ?>: Ksh <?= $row['amount1'] ?> | Fine: <?= $row['fine1'] ?><br>
        <?= $row['date2'] ?>: Ksh <?= $row['amount2'] ?> | Fine: <?= $row['fine2'] ?><br>
        <?= $row['date3'] ?>: Ksh <?= $row['amount3'] ?> | Fine: <?= $row['fine3'] ?>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

</body>
</html>
