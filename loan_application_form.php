<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: login.html");
    exit();
}

$userName = $_SESSION['user_name'];
$userId = $_SESSION['user_id'];
$userPhone = $_SESSION['user_phone'];
$userEmail = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Loan Application - SIDU</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f3f3f3;
      padding: 30px;
    }
    .container {
      max-width: 900px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #333;
    }
    .form-group {
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
    }
    input, textarea, select {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    button {
      background: #0066cc;
      color: white;
      padding: 12px 20px;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      width: 100%;
      margin-top: 20px;
    }
    button:hover {
      background: #004b99;
    }
    .section {
      background: #eef2f7;
      padding: 20px;
      margin-top: 30px;
      border-left: 5px solid #0066cc;
      border-radius: 6px;
    }
    .section strong {
      color: #0066cc;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>Loan Application Form - SIDU International</h2>

  <form action="submit_loan.php" method="POST" enctype="multipart/form-data">

    <div class="form-group">
      <label>Full Name</label>
      <input type="text" name="applicant_name" value="<?php echo htmlspecialchars($userName); ?>" required>
    </div>

    <div class="form-group">
      <label>National ID Number</label>
      <input type="text" name="id_number" value="<?php echo htmlspecialchars($userId); ?>" required>
    </div>

    <div class="form-group">
      <label>Phone Number</label>
      <input type="tel" name="phone_number" value="<?php echo htmlspecialchars($userPhone); ?>" required>
    </div>

    <div class="form-group">
      <label>Email Address</label>
      <input type="email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>" required>
    </div>

    <div class="form-group">
      <label>Loan Amount (KSh)</label>
      <input type="number" name="loan_amount" min="1000" required>
    </div>

    <div class="form-group">
      <label>Purpose of Loan</label>
      <select name="loan_purpose" required>
        <option value="">-- Select --</option>
        <option value="Business Expansion">Business Expansion</option>
        <option value="Stock Purchase">Stock Purchase</option>
        <option value="Medical">Medical</option>
        <option value="Education">Education</option>
        <option value="Emergency">Emergency</option>
      </select>
    </div>

    <div class="form-group">
      <label>Upload Passport Photo</label>
      <input type="file" name="passport_photo" accept="image/*" required>
    </div>

    <div class="section">
      <p>
        I, <strong><?php echo htmlspecialchars($userName); ?></strong>,
        holder of ID No. <strong><?php echo htmlspecialchars($userId); ?></strong>,
        and phone <strong><?php echo htmlspecialchars($userPhone); ?></strong>,
        hereby apply for a loan from SIDU International Ltd and declare that the information provided is accurate.
      </p>
    </div>

    <button type="submit">Submit Loan Application</button>
  </form>
</div>
</body>
</html>
