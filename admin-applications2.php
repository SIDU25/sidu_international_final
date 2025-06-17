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
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #1e1e2f;
            color: #f0f0f0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #70cfff;
            margin-bottom: 30px;
        }

        form {
            text-align: center;
            margin-bottom: 25px;
        }

        input[type="text"] {
            padding: 12px 15px;
            width: 320px;
            font-size: 15px;
            background: #2a2a3b;
            border: 1px solid #444;
            border-radius: 8px;
            color: #eee;
            outline: none;
        }

        input[type="text"]::placeholder {
            color: #aaa;
        }

        button {
            padding: 12px 25px;
            background: #2980b9;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            margin-left: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #3498db;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #2a2a3b;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 14px 18px;
            border-bottom: 1px solid #3e3e52;
            text-align: left;
            transition: all 0.3s ease;
        }

        th {
            background: #3a3a4d;
            color: #70cfff;
        }

        tbody tr {
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        tbody tr:hover {
            background-color: #33334c;
            transform: scale(1.01);
        }

        .view-link {
            color: #70cfff;
            font-weight: bold;
            text-decoration: none;
        }

        .view-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            th, td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            th::before, td::before {
                position: absolute;
                left: 15px;
                width: 45%;
                white-space: nowrap;
                font-weight: bold;
                color: #888;
            }

            th:nth-of-type(1)::before { content: "Applicant"; }
            th:nth-of-type(2)::before { content: "ID Number"; }
            th:nth-of-type(3)::before { content: "Phone"; }
            th:nth-of-type(4)::before { content: "Loan Amount"; }
            th:nth-of-type(5)::before { content: "Action"; }
        }
    </style>
</head>
<body>

<h2>📋 Sidu Loan Applications</h2>

<form method="get" action="admin-applications.php">
    <input type="text" name="search" placeholder="Search by Name, ID, or Phone" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>

<?php if ($result && $result->num_rows > 0): ?>
    <table>
        <thead>
        <tr>
            <th>Applicant</th>
            <th>ID Number</th>
            <th>Phone</th>
            <th>Loan Amount</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['id_number']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td>Ksh <?= number_format($row['loan_amount']) ?></td>
                <td><a class="view-link" href="print-application.php?id=<?= $row['id'] ?>" target="_blank">🔍 View / Print</a></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="text-align:center; color: #ccc;">No loan applications found.</p>
<?php endif; ?>

</body>
</html>
