<?php  
session_start();  
if (!isset($_SESSION['id_number'])) {  
    header('Location: login.php');  
    exit();  
}  
$email = $_SESSION['id_number'];  
$conn = new mysqli("localhost", "root", "", "sidu_portal");  
if ($conn->connect_error) {  
    die("Connection failed: " . $conn->connect_error);  
}  
$stmt = $conn->prepare("SELECT full_name, loan_amount, created_at, repayment_period, passport_photo FROM loan_applications WHERE email = ? ORDER BY created_at DESC LIMIT 1");  
$stmt->bind_param("s", $email);  
$stmt->execute();  
$result = $stmt->get_result();  
$loan = $result->fetch_assoc();  

if (!$loan) {  
    $loan = null;  
}  

$due_date = null;  
$progress = 0;  
if ($loan && $loan['created_at'] && $loan['repayment_period']) {  
    $date = new DateTime($loan['created_at']);  
    $due = clone $date;
    $date->modify('+' . intval($loan['repayment_period']) . ' months');  
    $due_date = $date->format('Y-m-d');  

    $now = new DateTime();
    $total_days = $date->diff($due)->days;
    $elapsed_days = $now->diff($due)->invert ? $total_days : $now->diff($due)->days;
    $progress = min(100, intval((($loan['repayment_period'] * 30 - $elapsed_days) / ($loan['repayment_period'] * 30)) * 100));
}  
?>  

<!DOCTYPE html>  
<html lang="en">  
<head>  
  <meta charset="UTF-8" />  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIDU Client Dashboard</title>  
  <style>
    :root {
      --sky-blue: #d5f1ff;
      --golden: #ffd700;
      --deep-blue: #002244;
      --light-blue: #007BFF;
      --dark-bg: #111;
      --light-text: #fff;
    }
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: var(--sky-blue);
      color: var(--deep-blue);
      transition: background-color 0.3s, color 0.3s;
    }
    body.dark-mode {
      background-color: var(--dark-bg);
      color: var(--light-text);
    }
    .container {
      max-width: 800px;
      margin: auto;
      padding: 2rem;
    }
    .header {
      text-align: center;
    }
    .profile-pic img {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid var(--golden);
    }
    .progress-bar {
      background: #e0e0e0;
      border-radius: 50px;
      overflow: hidden;
      height: 20px;
      margin-top: 10px;
    }
    .progress {
      height: 100%;
      background-color: var(--light-blue);
      width: <?= $progress ?>%;
      transition: width 0.5s ease-in-out;
    }
    .card {
      background: #fff;
      border-radius: 8px;
      padding: 1.5rem;
      margin-top: 1rem;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    body.dark-mode .card {
      background: #222;
    }
    .actions a {
      display: inline-block;
      margin: 1rem 1rem 0 0;
      padding: 12px 20px;
      background: var(--light-blue);
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
    }
    .toggle-mode {
      float: right;
      background: none;
      border: 2px solid var(--light-blue);
      padding: 8px 12px;
      border-radius: 4px;
      cursor: pointer;
      margin-top: -40px;
      margin-bottom: 20px;
      color: var(--light-blue);
    }
    .notification {
      background: var(--golden);
      padding: 10px;
      margin-top: 20px;
      border-radius: 6px;
      font-weight: bold;
    }
    .apply-btn {
      display: block;
      text-align: center;
      margin: 30px auto;
      padding: 15px 30px;
      background: #28a745;
      color: white;
      text-decoration: none;
      font-size: 1.1rem;
      font-weight: bold;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      transition: background 0.3s;
    }
    .apply-btn:hover {
      background: #218838;
    }
  </style>
</head>  
<body>
  <div class="container">
    <button class="toggle-mode" onclick="toggleDarkMode()">Toggle Dark Mode</button>
    <div class="header">
      <h2>Welcome, <?= htmlspecialchars($loan ? $loan['full_name'] : 'Sidu Client') ?></h2>
      <p>"Bridging the growth you can't acquire on your own."</p>
    </div>

    <div class="card profile-pic">
      <?php if ($loan && !empty($loan['passport_photo'])): ?>
        <img src="<?= htmlspecialchars($loan['passport_photo']) ?>" alt="Your Profile Picture">
      <?php else: ?>
        <img src="https://placehold.co/120x120/FFD700/000?text=No+Photo" alt="No Passport">
      <?php endif; ?>
      <form action="upload_profile.php" method="POST" enctype="multipart/form-data">
        <label for="photo">Update Profile Picture:</label>
        <input type="file" name="photo" accept="image/*">
        <button type="submit">Upload</button>
      </form>
    </div>

    <?php if ($loan): ?>
      <div class="card">
        <h3>Your Loan Overview</h3>
        <p><strong>Date Loan Taken:</strong> <?= date('d M Y', strtotime($loan['created_at'])) ?></p>
        <p><strong>Loan Amount:</strong> Ksh <?= number_format($loan['loan_amount']) ?></p>
        <p><strong>Loan Due Date:</strong> <?= $due_date ?></p>
        <div class="progress-bar"><div class="progress"></div></div>
      </div>

      <div class="notification">
        Reminder: Your due date is <?= $due_date ?>. Kindly make payments promptly to avoid penalties.
      </div>
    <?php else: ?>
      <a href="apply_loan.html" class="apply-btn">Apply for a New Loan</a>
    <?php endif; ?>

    <div class="card">
      <h3>Deposit Payment (M-Pesa)</h3>
      <p>Business No: <strong>400500</strong><br>Account No: <strong>74500</strong><br>Business Name: <strong>SIDU</strong></p>
      <a href="tel:*234#" class="actions">Open Sim Toolkit</a>
    </div>

    <div class="actions">
      <a href="index.html">Home</a>
      <a href="product.html">Products</a>
      <a href="edit_profile.php">Manage Profile</a>
    </div>
  </div>

  <script>
    function toggleDarkMode() {
      document.body.classList.toggle('dark-mode');
    }
  </script>
</body>  
</html>
