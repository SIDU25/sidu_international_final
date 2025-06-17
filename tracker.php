<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

// Check loan ID
$loan_id = isset($_GET['loan_id']) && is_numeric($_GET['loan_id']) ? intval($_GET['loan_id']) : null;
if (!$loan_id) {
    echo "Invalid loan ID.";
    exit();
}

// Fetch loan info
$loanStmt = $conn->prepare("SELECT full_name, loan_amount FROM loan_applications WHERE id = ?");
$loanStmt->bind_param("i", $loan_id);
$loanStmt->execute();
$loanResult = $loanStmt->get_result();
$loan = $loanResult->fetch_assoc();

if (!$loan) {
    echo "Loan application not found.";
    exit();
}

// Handle repayment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_date = $_POST['payment_date'];
    $amount_paid = $_POST['amount_paid'];
    $balance = $_POST['balance'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO loan_repayments (loan_id, payment_date, amount_paid, balance, notes) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issds", $loan_id, $payment_date, $amount_paid, $balance, $notes);
    $stmt->execute();
}

// Fetch repayments
$repayStmt = $conn->prepare("SELECT * FROM loan_repayments WHERE loan_id = ? ORDER BY payment_date ASC");
$repayStmt->bind_param("i", $loan_id);
$repayStmt->execute();
$repayments = $repayStmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Loan Tracker</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f9ff; }
        h2 { text-align: center; color: #005580; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #007BFF; color: white; }
        form { margin-top: 20px; background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
        input, textarea { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { padding: 10px 20px; background: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background: #218838; }
    </style>
</head>
<body>

<h2>Loan Tracker for <?= htmlspecialchars($loan['full_name']) ?> - Ksh <?= number_format($loan['loan_amount']) ?></h2>

<form method="post">
    <h3>Add New Repayment</h3>
    <label>Payment Date:</label>
    <input type="date" name="payment_date" required>

    <label>Amount Paid:</label>
    <input type="number" name="amount_paid" step="0.01" required>

    <label>Remaining Balance:</label>
    <input type="number" name="balance" step="0.01" required>

    <label>Notes:</label>
    <textarea name="notes" rows="3" placeholder="Optional comments"></textarea>

    <button type="submit">Save Repayment</button>
</form>

<?php if ($repayments->num_rows > 0): ?>
    <h3>Repayment History</h3>
    <table>
        <tr>
            <th>Date</th>
            <th>Amount Paid</th>
            <th>Balance</th>
            <th>Notes</th>
        </tr>
        <?php while ($row = $repayments->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['payment_date']) ?></td>
                <td>Ksh <?= number_format($row['amount_paid'], 2) ?></td>
                <td>Ksh <?= number_format($row['balance'], 2) ?></td>
                <td><?= htmlspecialchars($row['notes']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No repayments recorded yet.</p>
<?php endif; ?>

</body>
</html>
