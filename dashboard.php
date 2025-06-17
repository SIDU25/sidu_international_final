<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$database = "sidu_portal";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM loan_applications WHERE user_id = ? ORDER BY application_date DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$loan = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - Sidu International</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7fafd;
            color: #333;
            padding: 20px;
        }
        .dashboard {
            max-width: 700px;
            margin: auto;
            background: white;
            border: 1px solid #ccc;
            padding: 25px;
            border-radius: 8px;
        }
        h2 {
            color: #0c85d0;
        }
        .info {
            margin-top: 20px;
        }
        .info p {
            margin: 10px 0;
        }
        .logout {
            margin-top: 20px;
            text-align: right;
        }
        .logout a {
            background: #0c85d0;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
        }
        .logout a:hover {
            background: #0b6cad;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h2>Welcome to Your Dashboard</h2>
        <?php if ($loan): ?>
            <div class="info">
                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($loan['full_name']); ?></p>
                <p><strong>Loan Type:</strong> <?php echo htmlspecialchars($loan['loan_type']); ?></p>
                <p><strong>Amount:</strong> Ksh <?php echo number_format($loan['amount']); ?></p>
                <p><strong>Purpose:</strong> <?php echo htmlspecialchars($loan['purpose']); ?></p>
                <p><strong>Application Date:</strong> <?php echo $loan['application_date']; ?></p>
                <p><strong>Interest Rate:</strong> <?php echo $loan['interest_rate']; ?>%</p>
                <p><strong>Due Date:</strong> <?php echo $loan['due_date']; ?></p>
            </div>
        <?php else: ?>
            <p>No loan application submitted yet.</p>
        <?php endif; ?>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
