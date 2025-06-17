<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

include 'db.php';

$query = "SELECT lr.*, u.full_name FROM loan_repayments lr
          JOIN users u ON lr.user_id = u.id
          ORDER BY lr.date_paid DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Loan Repayment Records</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background: #007BFF; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        h2 { text-align: center; }
    </style>
</head>
<body>

<h2>Loan Repayment Records</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <table>
        <tr>
            <th>User</th>
            <th>Date Paid</th>
            <th>Amount Paid (Ksh)</th>
            <th>Balance (Ksh)</th>
            <th>Payment Method</th>
            <th>Remarks</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['date_paid']) ?></td>
            <td><?= number_format($row['amount_paid'], 2) ?></td>
            <td><?= number_format($row['balance'], 2) ?></td>
            <td><?= htmlspecialchars($row['payment_method']) ?></td>
            <td><?= htmlspecialchars($row['remarks']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p style="text-align:center;">No repayments found.</p>
<?php endif; ?>

</body>
</html>
