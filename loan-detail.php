<?php
$conn = new mysqli("localhost", "root", "", "sidu_portal");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM loan_applications WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$loan = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Loan Details</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background-color: #f2faff;
            padding: 20px;
        }
        h2 {
            color: #045eab;
        }
        .info-section {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 5px #ccc;
            margin-bottom: 20px;
        }
        .info-section h3 {
            color: goldenrod;
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px;
        }
        .info-section p {
            margin: 4px 0;
        }
        .uploaded img {
            max-width: 200px;
            margin-right: 15px;
            margin-bottom: 10px;
            display: block;
        }
        .uploaded a {
            display: inline-block;
            margin-top: 8px;
            color: #045eab;
        }
    </style>
</head>
<body>

<h2>Loan Application Details</h2>

<div class="info-section">
    <h3>Applicant Info</h3>
    <p><strong>Name:</strong> <?= htmlspecialchars($loan['full_name']) ?></p>
    <p><strong>ID Number:</strong> <?= htmlspecialchars($loan['id_number']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($loan['phone']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($loan['email']) ?></p>
    <p><strong>Town:</strong> <?= htmlspecialchars($loan['town']) ?></p>
</div>

<div class="info-section">
    <h3>Loan Details</h3>
    <p><strong>Amount:</strong> <?= htmlspecialchars($loan['loan_amount']) ?> </p>
    <p><strong>Purpose:</strong> <?= nl2br(htmlspecialchars($loan['loan_purpose'])) ?></p>
    <p><strong>Repayment Period:</strong> <?= htmlspecialchars($loan['repayment_period']) ?> months</p>
    <p><strong>Collateral:</strong> <?= nl2br(htmlspecialchars($loan['collateral'])) ?></p>
    <p><strong>Submitted on:</strong> <?= htmlspecialchars($loan['created_at']) ?></p>
</div>

<div class="info-section uploaded">
    <h3>Uploaded Files</h3>

    <?php if (!empty($loan['passport_photo'])): ?>
        <div>
            <strong>Passport Photo:</strong><br>
            <img src="<?= htmlspecialchars($loan['passport_photo']) ?>" alt="Passport Photo">
        </div>
    <?php endif; ?>

    <?php if (!empty($loan['id_front'])): ?>
        <div>
            <strong>ID Front:</strong><br>
            <img src="<?= htmlspecialchars($loan['id_front']) ?>" alt="ID Front">
        </div>
    <?php endif; ?>

    <?php if (!empty($loan['id_back'])): ?>
        <div>
            <strong>ID Back:</strong><br>
            <img src="<?= htmlspecialchars($loan['id_back']) ?>" alt="ID Back">
        </div>
    <?php endif; ?>

    <?php if (!empty($loan['collateral_doc'])): ?>
        <div>
            <strong>Collateral Document (PDF):</strong><br>
            <a href="<?= htmlspecialchars($loan['collateral_doc']) ?>" target="_blank">View PDF</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
