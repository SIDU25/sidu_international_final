<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $date_paid = $_POST['date_paid'];
    $amount_paid = $_POST['amount_paid'];
    $balance = $_POST['balance'];
    $payment_method = $_POST['payment_method'];
    $remarks = $_POST['remarks'];

    $stmt = $conn->prepare("INSERT INTO loan_repayments (user_id, date_paid, amount_paid, balance, payment_method, remarks) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdss", $user_id, $date_paid, $amount_paid, $balance, $payment_method, $remarks);

    if ($stmt->execute()) {
        $message = "Payment recorded successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Record Loan Payment</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        form { background: #fff; padding: 20px; border-radius: 6px; width: 400px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select, textarea { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px 15px; background: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .message { text-align: center; margin-bottom: 10px; color: green; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Record Loan Payment</h2>
<?php if ($message): ?>
    <p class="message"><?= $message ?></p>
<?php endif; ?>

<form method="post">
    <label>User ID:</label>
    <input type="number" name="user_id" required>

    <label>Date Paid:</label>
    <input type="date" name="date_paid" required>

    <label>Amount Paid (Ksh):</label>
    <input type="number" name="amount_paid" step="0.01" required>

    <label>Remaining Balance (Ksh):</label>
    <input type="number" name="balance" step="0.01" required>

    <label>Payment Method:</label>
    <select name="payment_method">
        <option value="M-Pesa">M-Pesa</option>
        <option value="Bank">Bank</option>
        <option value="Cash">Cash</option>
    </select>

    <label>Remarks:</label>
    <textarea name="remarks"></textarea>

    <button type="submit">Record Payment</button>
</form>

</body>
</html>
