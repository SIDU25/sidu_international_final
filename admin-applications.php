<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

$search = $_GET['search'] ?? '';
$result = null;

if (!empty($search)) {
    $searchTerm = "%$search%";
    $stmt = $conn->prepare("
        SELECT * FROM loan_applications
        WHERE full_name LIKE ? OR id_number LIKE ? OR phone LIKE ?
        ORDER BY id DESC
    ");
    if ($stmt) {
        $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    } else {
        die("Query preparation failed: " . $conn->error);
    }
} else {
    $result = $conn->query("SELECT * FROM loan_applications ORDER BY id DESC");
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loan Applications - Admin</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #1c1c1c;
            color: #e0e0e0;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #00c6ff;
            margin-bottom: 30px;
        }
        form {
            text-align: center;
            margin-bottom: 30px;
        }
        input[type="text"] {
            padding: 12px;
            width: 320px;
            font-size: 15px;
            border-radius: 5px;
            border: 1px solid #444;
            background: #2a2a2a;
            color: white;
        }
        button {
            padding: 12px 24px;
            background: #00c6ff;
            color: black;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            margin-left: 10px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #2a2a2a;
            box-shadow: 0 0 12px rgba(0,0,0,0.3);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 14px;
            text-align: left;
        }
        th {
            background: #333;
            color: #00c6ff;
            border-bottom: 2px solid #00c6ff;
        }
        tr {
            transition: background 0.3s ease;
        }
        tr:hover {
            background: #3b3b3b;
        }
        .view-link {
            color: #00c6ff;
            text-decoration: none;
            font-weight: bold;
        }
        .view-link:hover {
            text-decoration: underline;
        }
        .badge {
            display: inline-block;
            padding: 6px 10px;
            font-size: 13px;
            border-radius: 15px;
            color: white;
            font-weight: bold;
        }
        .badge-small {
            background: #3f51b5;
        }
        .badge-medium {
            background: #ff9800;
        }
        .badge-large {
            background: #4caf50;
        }
        .no-results {
            text-align: center;
            color: #888;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<h2>📋 All Loan Applications</h2>

<form method="get" action="admin-applications.php">
    <input type="text" name="search" placeholder="Search by Name, ID, or Phone" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">🔍 Search</button>
</form>

<?php if ($result && $result->num_rows > 0): ?>
    <table>
        <tr>
            <th>Applicant</th>
            <th>ID Number</th>
            <th>Phone</th>
            <th>Loan Amount</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
                $loan = (int)$row['loan_amount'];
                $badgeClass = $loan < 10000 ? 'badge-small' : ($loan <= 50000 ? 'badge-medium' : 'badge-large');
            ?>
            <tr>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['id_number']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><span class="badge <?= $badgeClass ?>">Ksh <?= number_format($loan) ?></span></td>
                <td><a class="view-link" href="print-application.php?id=<?= $row['id'] ?>" target="_blank">View / Print</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <div class="no-results">No loan applications found.</div>
<?php endif; ?>

</body>
</html>
