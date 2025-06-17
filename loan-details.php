<?php
session_start();
include 'db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

// Validate loan ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "No loan selected.";
    exit();
}

$loan_id = intval($_GET['id']);

// Fetch loan details
$stmt = $conn->prepare("SELECT * FROM loan_applications WHERE id = ?");
$stmt->bind_param("i", $loan_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Loan application not found.";
    exit();
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loan Details - Admin</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f8ff; }
        h2 { text-align: center; color: #005580; }
        .container {
            background: white;
            padding: 25px;
            margin: auto;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .field {
            margin-bottom: 12px;
        }
        .label {
            font-weight: bold;
            color: #333;
        }
        .btn-link {
            display: inline-block;
            margin-top: 20px;
            background: #007BFF;
            color: white;
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Loan Application Details</h2>

<div class="container">
    <?php foreach ($data as $key => $value): ?>
        <div class="field">
            <span class="label"><?= ucwords(str_replace("_", " ", $key)) ?>:</span>
            <?= nl2br(htmlspecialchars($value)) ?>
        </div>
    <?php endforeach; ?>

    <a class="btn-link" href="tracker.php?loan_id=<?= $loan_id ?>">Open Loan Tracker</a>
</div>

</body>
</html>
