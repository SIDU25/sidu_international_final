<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Change if needed
$dbname = "sidu_db"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed.']));
}

$id_number = isset($_GET['id_number']) ? trim($_GET['id_number']) : '';

if (empty($id_number)) {
    echo json_encode(['status' => 'error', 'message' => 'ID number is required.']);
    exit;
}

$sql = "SELECT * FROM loan_tracker WHERE id_number = ? ORDER BY date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_number);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['status' => 'success', 'data' => $data]);

$stmt->close();
$conn->close();
