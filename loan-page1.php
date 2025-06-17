<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Save data to session
    $_SESSION['loan_data']['application_date'] = $_POST['application_date'];
    // ... other fields ...
    header("Location: loan-page2.php");
    exit();
}
?>

  $_SESSION['loan_data']['application_date'] = $_POST['application_date'] ?? '';
  $_SESSION['loan_data']['full_name'] = $_POST['full_name'] ?? '';
  $_SESSION['loan_data']['id_number'] = $_POST['id_number'] ?? '';
  $_SESSION['loan_data']['nationality'] = $_POST['nationality'] ?? '';
  $_SESSION['loan_data']['phone'] = $_POST['phone'] ?? '';
  $_SESSION['loan_data']['email'] = $_POST['email'] ?? '';
  $_SESSION['loan_data']['town'] = $_POST['town'] ?? '';
  $_SESSION['loan_data']['location'] = $_POST['location'] ?? '';
  $_SESSION['loan_data']['landmark'] = $_POST['landmark'] ?? '';
  $_SESSION['loan_data']['ownership'] = $_POST['ownership'] ?? '';
  $_SESSION['loan_data']['business_name'] = $_POST['business_name'] ?? '';
  $_SESSION['loan_data']['years_operation'] = $_POST['years_operation'] ?? '';

  header("Location: loan-page2.php");
  exit();
}

// Optional: To repopulate the form if user comes back
$data = $_SESSION['loan_data'] ?? [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>SIDU Loan Form - Page 1</title>
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
    input, select {
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

<form method="post" action="loan-page1.php" autocomplete="off">
  <h2>SIDU Loan Application - Page 1 of 3</h2>

  <label>Application Date:</label>
  <input type="date" name="application_date" required value="<?= htmlspecialchars($data['application_date'] ?? '') ?>">

  <label>Full Name:</label>
  <input type="text" name="full_name" required value="<?= htmlspecialchars($data['full_name'] ?? '') ?>">

  <label>ID/Passport No:</label>
  <input type="text" name="id_number" required value="<?= htmlspecialchars($data['id_number'] ?? '') ?>">

  <label>Nationality:</label>
  <input type="text" name="nationality" required value="<?= htmlspecialchars($data['nationality'] ?? '') ?>">

  <label>Phone Number:</label>
  <input type="text" name="phone" required value="<?= htmlspecialchars($data['phone'] ?? '') ?>">

  <label>Email Address:</label>
  <input type="email" name="email" value="<?= htmlspecialchars($data['email'] ?? '') ?>">

  <label>Town / Residence:</label>
  <input type="text" name="town" required value="<?= htmlspecialchars($data['town'] ?? '') ?>">

  <label>Location / Plot:</label>
  <input type="text" name="location" required value="<?= htmlspecialchars($data['location'] ?? '') ?>">

  <label>Nearest Landmark:</label>
  <input type="text" name="landmark" value="<?= htmlspecialchars($data['landmark'] ?? '') ?>">

  <label>Business Ownership:</label>
  <select name="ownership">
    <option value="Sole Proprietorship" <?= (isset($data['ownership']) && $data['ownership'] == 'Sole Proprietorship') ? 'selected' : '' ?>>Sole Proprietorship</option>
    <option value="Family Business" <?= (isset($data['ownership']) && $data['ownership'] == 'Family Business') ? 'selected' : '' ?>>Family Business</option>
    <option value="Partnership" <?= (isset($data['ownership']) && $data['ownership'] == 'Partnership') ? 'selected' : '' ?>>Partnership</option>
    <option value="Limited Company" <?= (isset($data['ownership']) && $data['ownership'] == 'Limited Company') ? 'selected' : '' ?>>Limited Company</option>
  </select>

  <label>Business Name:</label>
  <input type="text" name="business_name" value="<?= htmlspecialchars($data['business_name'] ?? '') ?>">

  <label>Years in Operation:</label>
  <input type="number" name="years_operation" value="<?= htmlspecialchars($data['years_operation'] ?? '') ?>">

  <button type="submit">Save and Continue to loan-page2  →</button>
</form>

</body>
</html>
