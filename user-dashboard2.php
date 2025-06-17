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
    $loan = [  
        'full_name' => 'Sidu Client',  
        'loan_amount' => 'N/A',  
        'created_at' => null,  
        'repayment_period' => 0,  
        'passport_photo' => '',  
    ];  
}  

$due_date = null;  
$progress = 0;  
$days_remaining = 0;
if ($loan['created_at'] && $loan['repayment_period']) {  
    $date = new DateTime($loan['created_at']);  
    $due = clone $date;
    $date->modify('+' . intval($loan['repayment_period']) . ' months');  
    $due_date = $date->format('Y-m-d');  

    $now = new DateTime();
    $total_days = $date->diff($due)->days;
    $elapsed_days = $now->diff($due)->invert ? $total_days : $now->diff($due)->days;
    $days_remaining = $date->diff($now)->days;
    $progress = min(100, max(0, intval((($loan['repayment_period'] * 30 - $elapsed_days) / ($loan['repayment_period'] * 30)) * 100)));
}  
?>  

<!DOCTYPE html>  
<html lang="en">  
<head>  
  <meta charset="UTF-8" />  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIDU Dashboard - Modern Finance</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
      --glass-bg: rgba(255, 255, 255, 0.1);
      --glass-border: rgba(255, 255, 255, 0.2);
      --text-primary: #2d3748;
      --text-secondary: #4a5568;
      --bg-light: #f7fafc;
      --shadow-soft: 0 10px 25px rgba(0, 0, 0, 0.1);
      --shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.15);
      --border-radius: 16px;
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    [data-theme="dark"] {
      --text-primary: #f7fafc;
      --text-secondary: #e2e8f0;
      --bg-light: #1a202c;
      --glass-bg: rgba(0, 0, 0, 0.2);
      --glass-border: rgba(255, 255, 255, 0.1);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: var(--bg-light);
      color: var(--text-primary);
      line-height: 1.6;
      transition: var(--transition);
      min-height: 100vh;
    }

    [data-theme="dark"] body {
      background: linear-gradient(135deg, #0c4a6e 0%, #1e3a8a 50%, #581c87 100%);
    }

    .dashboard-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem;
      position: relative;
    }

    /* Floating particles background */
    .particles {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -1;
    }

    .particle {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(180deg); }
    }

    /* Header */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 3rem;
      padding: 2rem;
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow-soft);
    }

    .welcome-section h1 {
      font-size: 2.5rem;
      font-weight: 700;
      background: var(--primary-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 0.5rem;
    }

    .welcome-section p {
      color: var(--text-secondary);
      font-size: 1.1rem;
      font-weight: 500;
    }

    .header-controls {
      display: flex;
      gap: 1rem;
      align-items: center;
    }

    .theme-toggle {
      background: var(--glass-bg);
      border: 1px solid var(--glass-border);
      padding: 0.8rem;
      border-radius: 12px;
      cursor: pointer;
      font-size: 1.2rem;
      color: var(--text-primary);
      transition: var(--transition);
      backdrop-filter: blur(10px);
    }

    .theme-toggle:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-hover);
    }

    .notification-bell {
      position: relative;
      background: var(--warning-gradient);
      border: none;
      padding: 0.8rem;
      border-radius: 12px;
      color: white;
      cursor: pointer;
      font-size: 1.2rem;
      transition: var(--transition);
    }

    .notification-bell:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-hover);
    }

    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background: #ff4757;
      color: white;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      font-size: 0.8rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Grid Layout */
    .dashboard-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
      margin-bottom: 2rem;
    }

    .full-width {
      grid-column: 1 / -1;
    }

    /* Glass Cards */
    .glass-card {
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      border-radius: var(--border-radius);
      padding: 2rem;
      box-shadow: var(--shadow-soft);
      transition: var(--transition);
      position: relative;
      overflow: hidden;
    }

    .glass-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    }

    .glass-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-hover);
    }

    /* Profile Card */
    .profile-card {
      text-align: center;
      background: var(--primary-gradient);
      color: white;
    }

    .profile-avatar {
      position: relative;
      display: inline-block;
      margin-bottom: 1.5rem;
    }

    .profile-avatar img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
      transition: var(--transition);
    }

    .avatar-edit-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.7);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: var(--transition);
      cursor: pointer;
      color: white;
      font-size: 1.5rem;
    }

    .profile-avatar:hover .avatar-edit-overlay {
      opacity: 1;
    }

    .profile-avatar:hover img {
      transform: scale(1.05);
    }

    .avatar-status {
      position: absolute;
      bottom: 5px;
      right: 5px;
      width: 24px;
      height: 24px;
      background: #10b981;
      border-radius: 50%;
      border: 3px solid white;
    }

    .upload-section {
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .upload-options {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      justify-content: center;
    }

    .file-upload {
      position: relative;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      cursor: pointer;
      overflow: hidden;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.2);
      padding: 0.8rem 1.2rem;
      transition: var(--transition);
      border: none;
      color: white;
      font-weight: 500;
      font-size: 0.9rem;
    }

    .file-upload:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: translateY(-2px);
    }

    /* Loan Overview */
    .loan-overview {
      background: var(--success-gradient);
      color: white;
    }

    .loan-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .stat-item {
      text-align: center;
      padding: 1rem;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      backdrop-filter: blur(10px);
    }

    .stat-value {
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .stat-label {
      font-size: 0.9rem;
      opacity: 0.9;
    }

    /* Progress Bar */
    .progress-container {
      margin-top: 1.5rem;
    }

    .progress-bar {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50px;
      height: 12px;
      overflow: hidden;
      position: relative;
    }

    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, #fff, rgba(255,255,255,0.8));
      border-radius: 50px;
      width: <?= $progress ?>%;
      transition: width 1s ease-out;
      position: relative;
    }

    .progress-fill::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
      animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }

    .progress-text {
      text-align: center;
      margin-top: 0.5rem;
      font-weight: 600;
    }

    /* Services Grid */
    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
    }

    .service-card {
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      border-radius: var(--border-radius);
      padding: 2rem;
      text-align: center;
      transition: var(--transition);
      position: relative;
      overflow: hidden;
    }

    .service-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: var(--primary-gradient);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .service-card:hover::before {
      opacity: 0.1;
    }

    .service-card:hover {
      transform: translateY(-8px);
      box-shadow: var(--shadow-hover);
    }

    .service-icon {
      font-size: 3rem;
      margin-bottom: 1rem;
      background: var(--primary-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .service-card h3 {
      margin-bottom: 1rem;
      font-weight: 600;
    }

    .service-card p {
      color: var(--text-secondary);
      margin-bottom: 1.5rem;
    }

    /* Buttons */
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.8rem 1.5rem;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: var(--transition);
      position: relative;
      overflow: hidden;
    }

    .btn-primary {
      background: var(--primary-gradient);
      color: white;
    }

    .btn-secondary {
      background: var(--secondary-gradient);
      color: white;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-hover);
    }

    .btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .btn:hover::before {
      left: 100%;
    }

    /* Payment Section */
    .payment-card {
      background: var(--warning-gradient);
      color: white;
    }

    .payment-details {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .payment-item {
      background: rgba(255, 255, 255, 0.1);
      padding: 1rem;
      border-radius: 10px;
      text-align: center;
    }

    .payment-item strong {
      display: block;
      font-size: 1.2rem;
      margin-bottom: 0.5rem;
    }

    /* Quick Actions */
    .quick-actions {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
    }

    /* Notifications */
    .notification-panel {
      position: fixed;
      top: 100px;
      right: -400px;
      width: 350px;
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      border-radius: var(--border-radius);
      padding: 1.5rem;
      box-shadow: var(--shadow-hover);
      transition: right 0.3s ease;
      z-index: 1000;
    }

    .notification-panel.active {
      right: 20px;
    }

    .notification-item {
      padding: 1rem;
      border-bottom: 1px solid var(--glass-border);
      margin-bottom: 1rem;
    }

    .notification-item:last-child {
      border-bottom: none;
      margin-bottom: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .dashboard-container {
        padding: 1rem;
      }

      .dashboard-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
      }

      .header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
      }

      .welcome-section h1 {
        font-size: 2rem;
      }

      .loan-stats {
        grid-template-columns: 1fr 1fr;
      }

      .services-grid {
        grid-template-columns: 1fr;
      }

      .quick-actions {
        grid-template-columns: 1fr;
      }
    }

    /* Loading Animation */
    .loading {
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 3px solid rgba(255,255,255,.3);
      border-radius: 50%;
      border-top-color: #fff;
      animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>  
<body data-theme="light">
  <!-- Floating Particles -->
  <div class="particles" id="particles"></div>

  <!-- Notification Panel -->
  <div class="notification-panel" id="notificationPanel">
    <h3><i class="fas fa-bell"></i> Notifications</h3>
    <div class="notification-item">
      <strong>Payment Reminder</strong>
      <p>Your loan payment is due in <?= $days_remaining ?> days</p>
    </div>
    <div class="notification-item">
      <strong>New Feature</strong>
      <p>Investment opportunities now available!</p>
    </div>
    <div class="notification-item">
      <strong>Profile Update</strong>
      <p>Please update your profile picture</p>
    </div>
  </div>

  <div class="dashboard-container">
    <!-- Header -->
    <header class="header">
      <div class="welcome-section">
        <h1>Welcome back, <?= htmlspecialchars(explode(' ', $loan['full_name'])[0]) ?>!</h1>
        <p><i class="fas fa-quote-left"></i> Bridging the growth you can't acquire on your own <i class="fas fa-quote-right"></i></p>
      </div>
      <div class="header-controls">
        <button class="notification-bell" onclick="toggleNotifications()">
          <i class="fas fa-bell"></i>
          <span class="notification-badge">3</span>
        </button>
        <button class="theme-toggle" onclick="toggleTheme()">
          <i class="fas fa-moon" id="themeIcon"></i>
        </button>
      </div>
    </header>

    <!-- Main Dashboard Grid -->
    <div class="dashboard-grid">
      <!-- Profile Card -->
      <div class="glass-card profile-card">
        <div class="profile-avatar">
          <?php if (!empty($loan['passport_photo'])): ?>
            <img src="<?= htmlspecialchars($loan['passport_photo']) ?>" alt="Profile Picture" id="profileImage">
          <?php else: ?>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($loan['full_name']) ?>&size=120&background=667eea&color=ffffff&bold=true" alt="Default Avatar" id="profileImage">
          <?php endif; ?>
          <div class="avatar-status"></div>
          <div class="avatar-edit-overlay" onclick="triggerFileUpload()">
            <i class="fas fa-camera"></i>
          </div>
        </div>
        <h2><?= htmlspecialchars($loan['full_name']) ?></h2>
        <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($email) ?></p>
        
        <div class="upload-section">
          <form action="upload_profile.php" method="POST" enctype="multipart/form-data" id="profileForm">
            <input type="file" name="photo" accept="image/*" id="profilePhotoInput" style="display: none;" onchange="handleProfileUpload(this)">
            <div class="upload-options">
              <button type="button" class="file-upload" onclick="triggerFileUpload()">
                <i class="fas fa-upload"></i> Upload New Photo
              </button>
              <button type="button" class="file-upload" onclick="removeProfilePhoto()" style="background: rgba(255, 99, 99, 0.2); margin-left: 10px;">
                <i class="fas fa-trash"></i> Remove Photo
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Loan Overview Card -->
      <div class="glass-card loan-overview">
        <h3><i class="fas fa-chart-line"></i> Loan Overview</h3>
        
        <?php if ($loan['created_at']): ?>
          <div class="loan-stats">
            <div class="stat-item">
              <div class="stat-value">KSh <?= number_format($loan['loan_amount']) ?></div>
              <div class="stat-label">Loan Amount</div>
            </div>
            <div class="stat-item">
              <div class="stat-value"><?= $loan['repayment_period'] ?></div>
              <div class="stat-label">Months</div>
            </div>
            <div class="stat-item">
              <div class="stat-value"><?= $days_remaining ?></div>
              <div class="stat-label">Days Left</div>
            </div>
            <div class="stat-item">
              <div class="stat-value"><?= $progress ?>%</div>
              <div class="stat-label">Progress</div>
            </div>
          </div>

          <div class="progress-container">
            <div class="progress-bar">
              <div class="progress-fill"></div>
            </div>
            <div class="progress-text">
              Loan taken on <?= date('M d, Y', strtotime($loan['created_at'])) ?>
            </div>
          </div>
        <?php else: ?>
          <div style="text-align: center; padding: 2rem;">
            <i class="fas fa-info-circle" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p>No active loans found</p>
            <a href="apply_loan.html" class="btn btn-primary" style="margin-top: 1rem;">
              <i class="fas fa-plus"></i> Apply for Loan
            </a>
          </div>
        <?php endif; ?>
      </div>

      <!-- Services Section -->
      <div class="glass-card full-width">
        <h3><i class="fas fa-concierge-bell"></i> Our Services</h3>
        <div class="services-grid">
          <div class="service-card">
            <div class="service-icon">
              <i class="fas fa-hand-holding-usd"></i>
            </div>
            <h3>Quick Loans</h3>
            <p>Get instant access to funds with competitive rates and flexible terms</p>
            <a href="apply_loan.html" class="btn btn-primary">
              <i class="fas fa-rocket"></i> Apply Now
            </a>
          </div>

          <div class="service-card">
            <div class="service-icon">
              <i class="fas fa-chart-pie"></i>
            </div>
            <h3>Investment Plans</h3>
            <p>Grow your wealth with our diverse investment opportunities</p>
            <button class="btn btn-secondary">
              <i class="fas fa-seedling"></i> Invest Now
            </button>
          </div>

          <div class="service-card">
            <div class="service-icon">
              <i class="fas fa-shield-alt"></i>
            </div>
            <h3>Insurance Cover</h3>
            <p>Protect your future with comprehensive insurance solutions</p>
            <button class="btn btn-primary">
              <i class="fas fa-umbrella"></i> Learn More
            </button>
          </div>

          <div class="service-card">
            <div class="service-icon">
              <i class="fas fa-graduation-cap"></i>
            </div>
            <h3>Financial Literacy</h3>
            <p>Access educational resources to improve your financial knowledge</p>
            <button class="btn btn-secondary">
              <i class="fas fa-book-open"></i> Start Learning
            </button>
          </div>
        </div>
      </div>

      <!-- Payment Information -->
      <div class="glass-card payment-card">
        <h3><i class="fas fa-mobile-alt"></i> Make Payment (M-Pesa)</h3>
        <div class="payment-details">
          <div class="payment-item">
            <strong>400500</strong>
            <span>Business No</span>
          </div>
          <div class="payment-item">
            <strong>74500</strong>
            <span>Account No</span>
          </div>
          <div class="payment-item">
            <strong>SIDU</strong>
            <span>Business Name</span>
          </div>
        </div>
        <div style="text-align: center;">
          <a href="tel:*234#" class="btn" style="background: rgba(255,255,255,0.2); color: white;">
            <i class="fas fa-phone"></i> Open SIM Toolkit
          </a>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="glass-card">
        <h3><i class="fas fa-lightning-bolt"></i> Quick Actions</h3>
        <div class="quick-actions">
          <a href="index.html" class="btn btn-primary">
            <i class="fas fa-home"></i> Home
          </a>
          <a href="product.html" class="btn btn-secondary">
            <i class="fas fa-box"></i> Products
          </a>
          <a href="edit_profile.php" class="btn btn-primary">
            <i class="fas fa-user-edit"></i> Edit Profile
          </a>
          <button class="btn btn-secondary" onclick="showComingSoon()">
            <i class="fas fa-headset"></i> Support
          </button>
        </div>
      </div>
    </div>

    <!-- Due Date Reminder -->
    <?php if ($due_date): ?>
    <div class="glass-card" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; margin-top: 2rem;">
      <div style="display: flex; align-items: center; gap: 1rem;">
        <i class="fas fa-clock" style="font-size: 2rem;"></i>
        <div>
          <h3>Payment Reminder</h3>
          <p>Your next payment is due on <strong><?= date('M d, Y', strtotime($due_date)) ?></strong></p>
          <p>Make sure to pay on time to avoid penalties and maintain your credit score!</p>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <script>
    // Theme Toggle
    function toggleTheme() {
      const body = document.body;
      const themeIcon = document.getElementById('themeIcon');
      const currentTheme = body.getAttribute('data-theme');
      
      if (currentTheme === 'light') {
        body.setAttribute('data-theme', 'dark');
        themeIcon.className = 'fas fa-sun';
        localStorage.setItem('theme', 'dark');
      } else {
        body.setAttribute('data-theme', 'light');
        themeIcon.className = 'fas fa-moon';
        localStorage.setItem('theme', 'light');
      }
    }

    // Load saved theme
    document.addEventListener('DOMContentLoaded', function() {
      const savedTheme = localStorage.getItem('theme') || 'light';
      document.body.setAttribute('data-theme', savedTheme);
      document.getElementById('themeIcon').className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    });

    // Notification Panel
    function toggleNotifications() {
      const panel = document.getElementById('notificationPanel');
      panel.classList.toggle('active');
    }

    // Close notifications when clicking outside
    document.addEventListener('click', function(event) {
      const panel = document.getElementById('notificationPanel');
      const button = document.querySelector('.notification-bell');
      
      if (!panel.contains(event.target) && !button.contains(event.target)) {
        panel.classList.remove('active');
      }
    });

    // Floating Particles
    function createParticles() {
      const particlesContainer = document.getElementById('particles');
      const particleCount = 20;

      for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = Math.random() * 100 + '%';
        particle.style.width = Math.random() * 4 + 2 + 'px';
        particle.style.height = particle.style.width;
        particle.style.animationDelay = Math.random() * 6 + 's';
        particlesContainer.appendChild(particle);
      }
    }

    // Profile Photo Management
    function triggerFileUpload() {
      document.getElementById('profilePhotoInput').click();
    }

    function handleProfileUpload(input) {
      if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file type
        if (!file.type.match('image.*')) {
          alert('Please select a valid image file.');
          return;
        }
        
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
          alert('File size must be less than 5MB.');
          return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('profileImage').src = e.target.result;
          showUploadConfirmation(file.name);
        };
        reader.readAsDataURL(file);
      }
    }

    function showUploadConfirmation(fileName) {
      const confirmation = document.createElement('div');
      confirmation.innerHTML = `
        <div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); 
                    background: var(--glass-bg); backdrop-filter: blur(20px); 
                    border: 1px solid var(--glass-border); border-radius: 16px; 
                    padding: 2rem; text-align: center; z-index: 1001; color: var(--text-primary);
                    box-shadow: var(--shadow-hover);">
          <i class="fas fa-image" style="font-size: 3rem; color: #667eea; margin-bottom: 1rem;"></i>
          <h3>Upload New Photo?</h3>
          <p style="margin: 1rem 0; color: var(--text-secondary);">Selected: ${fileName}</p>
          <div style="display: flex; gap: 1rem; justify-content: center;">
            <button onclick="confirmUpload()" class="btn btn-primary">
              <i class="fas fa-check"></i> Upload
            </button>
            <button onclick="cancelUpload()" class="btn" style="background: #6c757d; color: white;">
              <i class="fas fa-times"></i> Cancel
            </button>
          </div>
        </div>
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                    background: rgba(0,0,0,0.5); z-index: 1000;" onclick="cancelUpload()"></div>
      `;
      confirmation.id = 'uploadConfirmation';
      document.body.appendChild(confirmation);
    }

    function confirmUpload() {
      const form = document.getElementById('profileForm');
      const formData = new FormData(form);
      
      // Show loading state
      const confirmation = document.getElementById('uploadConfirmation');
      confirmation.querySelector('div').innerHTML = `
        <div style="text-align: center;">
          <div class="loading" style="margin: 2rem auto;"></div>
          <p>Uploading your photo...</p>
        </div>
      `;
      
      // Submit form via AJAX (you'll need to implement the server-side handling)
      fetch('upload_profile.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showSuccessMessage('Profile photo updated successfully!');
        } else {
          showErrorMessage(data.message || 'Upload failed. Please try again.');
        }
        document.body.removeChild(confirmation);
      })
      .catch(error => {
        showErrorMessage('Upload failed. Please try again.');
        document.body.removeChild(confirmation);
      });
    }

    function cancelUpload() {
      const confirmation = document.getElementById('uploadConfirmation');
      if (confirmation) {
        document.body.removeChild(confirmation);
      }
      
      // Reset the image to original
      const originalSrc = '<?php if (!empty($loan["passport_photo"])) echo htmlspecialchars($loan["passport_photo"]); else echo "https://ui-avatars.com/api/?name=" . urlencode($loan["full_name"]) . "&size=120&background=667eea&color=ffffff&bold=true"; ?>';
      document.getElementById('profileImage').src = originalSrc;
      
      // Reset file input
      document.getElementById('profilePhotoInput').value = '';
    }

    function removeProfilePhoto() {
      if (confirm('Are you sure you want to remove your profile photo?')) {
        // Set to default avatar
        const defaultSrc = 'https://ui-avatars.com/api/?name=<?= urlencode($loan["full_name"]) ?>&size=120&background=667eea&color=ffffff&bold=true';
        document.getElementById('profileImage').src = defaultSrc;
        
        // Send request to server to remove photo
        fetch('remove_profile_photo.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            showSuccessMessage('Profile photo removed successfully!');
          } else {
            showErrorMessage('Failed to remove photo. Please try again.');
          }
        })
        .catch(error => {
          showErrorMessage('Failed to remove photo. Please try again.');
        });
      }
    }

    function showSuccessMessage(message) {
      showNotification(message, 'success');
    }

    function showErrorMessage(message) {
      showNotification(message, 'error');
    }

    function showNotification(message, type) {
      const notification = document.createElement('div');
      const bgColor = type === 'success' ? '#10b981' : '#ef4444';
      
      notification.innerHTML = `
        <div style="position: fixed; top: 20px; right: 20px; z-index: 1002;
                    background: ${bgColor}; color: white; padding: 1rem 1.5rem;
                    border-radius: 12px; box-shadow: var(--shadow-hover);
                    display: flex; align-items: center; gap: 0.5rem;
                    animation: slideIn 0.3s ease;">
          <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
          ${message}
        </div>
      `;
      
      document.body.appendChild(notification);
      
      setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
          if (document.body.contains(notification)) {
            document.body.removeChild(notification);
          }
        }, 300);
      }, 3000);
    }

    // Smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Progress bar animation
    function animateProgressBar() {
      const progressBar = document.querySelector('.progress-fill');
      if (progressBar) {
        progressBar.style.width = '0%';
        setTimeout(() => {
          progressBar.style.width = '<?= $progress ?>%';
        }, 500);
      }
    }

    // Card hover effects
    function addCardEffects() {
      const cards = document.querySelectorAll('.glass-card, .service-card');
      cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
          this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
          this.style.transform = 'translateY(0) scale(1)';
        });
      });
    }

    // Initialize everything
    document.addEventListener('DOMContentLoaded', function() {
      createParticles();
      animateProgressBar();
      addCardEffects();
      
      // Add loading state to buttons
      document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
          if (this.href && !this.href.includes('#')) {
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="loading"></span> Loading...';
            
            setTimeout(() => {
              this.innerHTML = originalText;
            }, 2000);
          }
        });
      });

      // Add success message for file uploads
      const fileInputs = document.querySelectorAll('input[type="file"]');
      fileInputs.forEach(input => {
        input.addEventListener('change', function() {
          if (this.files.length > 0) {
            const fileName = this.files[0].name;
            const label = this.closest('label');
            const originalText = label.innerHTML;
            label.innerHTML = `<i class="fas fa-check"></i> ${fileName} selected`;
            
            setTimeout(() => {
              label.innerHTML = originalText;
            }, 3000);
          }
        });
      });

      // Auto-hide notifications after 5 seconds
      setTimeout(() => {
        const panel = document.getElementById('notificationPanel');
        if (panel.classList.contains('active')) {
          panel.classList.remove('active');
        }
      }, 5000);

      // Add ripple effect to buttons
      document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function(e) {
          const ripple = document.createElement('span');
          const rect = this.getBoundingClientRect();
          const size = Math.max(rect.width, rect.height);
          const x = e.clientX - rect.left - size / 2;
          const y = e.clientY - rect.top - size / 2;
          
          ripple.style.width = ripple.style.height = size + 'px';
          ripple.style.left = x + 'px';
          ripple.style.top = y + 'px';
          ripple.classList.add('ripple');
          
          this.appendChild(ripple);
          
          setTimeout(() => {
            ripple.remove();
          }, 600);
        });
      });
    });

    // Add CSS for ripple effect
    const style = document.createElement('style');
    style.textContent = `
      .btn {
        position: relative;
        overflow: hidden;
      }
      
      .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
      }
      
      @keyframes ripple-animation {
        to {
          transform: scale(4);
          opacity: 0;
        }
      }
      
      /* Additional responsive styles */
      @media (max-width: 480px) {
        .dashboard-container {
          padding: 0.5rem;
        }
        
        .glass-card {
          padding: 1rem;
        }
        
        .welcome-section h1 {
          font-size: 1.5rem;
        }
        
        .stat-item {
          padding: 0.5rem;
        }
        
        .stat-value {
          font-size: 1.4rem;
        }
        
        .service-icon {
          font-size: 2rem;
        }
        
        .notification-panel {
          width: calc(100vw - 2rem);
          right: -100vw;
        }
        
        .notification-panel.active {
          right: 1rem;
        }
      }
      
      /* Enhanced animations */
      @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
      }
      
      .notification-badge {
        animation: pulse 2s infinite;
      }
      
      /* Scroll animations */
      .glass-card {
        opacity: 0;
        transform: translateY(30px);
        animation: slideUp 0.6s ease forwards;
      }
      
      .glass-card:nth-child(1) { animation-delay: 0.1s; }
      .glass-card:nth-child(2) { animation-delay: 0.2s; }
      .glass-card:nth-child(3) { animation-delay: 0.3s; }
      .glass-card:nth-child(4) { animation-delay: 0.4s; }
      
      @keyframes slideUp {
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
    `;
    document.head.appendChild(style);
  </script>
</body>  
</html>