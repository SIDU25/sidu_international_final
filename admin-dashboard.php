<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin-login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - SIDU International</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            animation: fadeInBody 1s ease-in-out;
        }

        @keyframes fadeInBody {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        header {
            background: linear-gradient(to right, #00bfff, #ffd700);
            color: #fff;
            padding: 20px;
            text-align: center;
            position: relative;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        header h1 {
            margin: 0;
            font-size: 28px;
        }

        header p {
            margin: 5px 0 0;
        }

        .logout-button {
            position: absolute;
            right: 20px;
            top: 20px;
            background: #ffd700;
            color: #004080;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .logout-button:hover {
            background: #e6c200;
        }

        main {
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            padding: 25px;
            transition: 0.4s ease;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.25);
        }

        .card h3 {
            margin-top: 0;
            color: #004080;
            font-size: 20px;
        }

        .card svg {
            width: 40px;
            height: 40px;
            margin-bottom: 10px;
            fill: #00bfff;
        }

        .card a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: white;
            background: linear-gradient(to right, #00bfff, #ffd700);
            padding: 10px 15px;
            border-radius: 6px;
            font-weight: bold;
        }

        .card a:hover {
            background: linear-gradient(to right, #009acd, #e6c200);
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #555;
            background: rgba(255, 255, 255, 0.7);
            font-weight: bold;
        }
    </style>
</head>
<body>

<header>
    <h1>SIDU International - Admin Panel</h1>
    <p>Manage Loans, Applications & System Actions</p>
    <form method="post" action="admin-logout.php" style="display:inline;">
        <button type="submit" class="logout-button">Logout</button>
    </form>
</header>

<main>
    <!-- View Applications -->
    <div class="card">
        <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14l4-4h12c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
        <h3>View All Applications</h3>
        <a href="admin-applications.php">Open</a>
    </div>

    <!-- Status Management -->
    <div class="card">
        <svg viewBox="0 0 24 24"><path d="M12 7V3L8 7h3v4l4-4h-3z"/></svg>
        <h3>Loan Status Manager</h3>
        <a href="loan-status.php">Update Status</a>
    </div>

    <!-- Uploads -->
    <div class="card">
        <svg viewBox="0 0 24 24"><path d="M20 6h-3l-2-2H9L7 6H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/></svg>
        <h3>View All Uploads</h3>
        <a href="view-uploads.php">Browse Files</a>
    </div>

    <!-- Print Center -->
    <div class="card">
        <svg viewBox="0 0 24 24"><path d="M19 8H5c-1.1 0-2 .9-2 2v6h4v4h10v-4h4v-6c0-1.1-.9-2-2-2zM8 18v-4h8v4H8z"/></svg>
        <h3>Print Center</h3>
        <a href="print-center.php">Print PDF / Excel / Word</a>
    </div>

    <!-- Statements -->
    <div class="card">
        <svg viewBox="0 0 24 24"><path d="M3 6v12h18V6H3zm2 2h14v8H5V8z"/></svg>
        <h3>View Statements</h3>
        <a href="view-statements.php">View</a>
    </div>

    <!-- Disbursements -->
    <div class="card">
        <svg viewBox="0 0 24 24"><path d="M15 14h2v5h-2v-5zM7 14h2v5H7v-5zm4-6h2v11h-2V8z"/></svg>
        <h3>Disbursed Loans</h3>
        <a href="disbursed-loans.php">Track</a>
    </div>

    <!-- Pending Loans -->
    <div class="card">
        <svg viewBox="0 0 24 24"><path d="M12 4C7.03 4 3 8.03 3 13s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9zm-1 14v-6h2v6h-2zm0-8V8h2v2h-2z"/></svg>
        <h3>Pending Loans</h3>
        <a href="pending-loans.php">Review</a>
    </div>

    <!-- Notifications -->
    <div class="card">
        <svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 0 0-10 10h3l-4 4-4-4h3a10 10 0 1 0 18.3 6.7l-1.5-1.3A8 8 0 1 1 12 2z"/></svg>
        <h3>User Notifications</h3>
        <a href="send-messages.php">Send Messages</a>
    </div>
</main>

<footer>
    SIDU International Ltd &copy; <?= date('Y') ?>. All Rights Reserved.
</footer>

</body>
</html>
