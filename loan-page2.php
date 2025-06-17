<?php
session_start();

// Redirect back to page1 if no session data (to prevent skipping)
if (!isset($_SESSION['loan_data'])) {
    header("Location: loan-form 1.php");
    exit();
};

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $_SESSION['loan_data']['loan_amount'] = $_POST['loan_amount'] ?? '';
    $_SESSION['loan_data']['loan_status'] = $_POST['loan_status'] ?? '';
    $_SESSION['loan_data']['repayment_period'] = $_POST['repayment_period'] ?? '';
    $_SESSION['loan_data']['loan_purpose'] = $_POST['loan_purpose'] ?? '';
    $_SESSION['loan_data']['repayment_plan'] = $_POST['repayment_plan'] ?? '';

    header("Location: loan-form 3.php");
    exit();
}

$data = $_SESSION['loan_data'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>SIDU Loan Form - Page 2</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f9ff;
      padding: 30px;
    }
    form {
      max-width: 900px;
      margin: auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    }
    h2 {
      text-align: center;
      color: #007BFF;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }
    input, select, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 5px;
      border: 1px solid #aaa;
    }
    button {
      margin-top: 20px;
      background: goldenrod;
      color: white;
      padding: 12px;
      width: 100%;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<form method="post" action="loan-page2.php" autocomplete="off">
  <h2>SIDU Loan Application - Page 2 of 3</h2>

  <label>Loan Amount:</label>
  <input type="number" step="0.01" name="loan_amount" required value="<?= htmlspecialchars($data['loan_amount'] ?? '') ?>">

  <label>Loan Status:</label>
  <select name="loan_status" required>
    <option value="">-- Select Status --</option>
    <option value="Pending" <?= (isset($data['loan_status']) && $data['loan_status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
    <option value="Approved" <?= (isset($data['loan_status']) && $data['loan_status'] == 'Approved') ? 'selected' : '' ?>>Approved</option>
    <option value="Rejected" <?= (isset($data['loan_status']) && $data['loan_status'] == 'Rejected') ? 'selected' : '' ?>>Rejected</option>
  </select>

  <label>Repayment Period:</label>
  <input type="text" name="repayment_period" required value="<?= htmlspecialchars($data['repayment_period'] ?? '') ?>">

  <label>Loan Purpose:</label>
  <textarea name="loan_purpose" rows="4" required><?= htmlspecialchars($data['loan_purpose'] ?? '') ?></textarea>

  <label>Repayment Plan:</label>
  <textarea name="repayment_plan" rows="4" required><?= htmlspecialchars($data['repayment_plan'] ?? '') ?></textarea>

  <button type="submit">Save and Continue to Page 3 →</button>
</form>

</body>
</html>
