<?php
// submit-loan.php
$host = "localhost";
$user = "root";
$pass = "";
$db = "sidu_portal";

// Connect to DB
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle file uploads
function uploadFile($field) {
    if (isset($_FILES[$field]) && $_FILES[$field]['error'] === 0) {
        $filename = basename($_FILES[$field]['name']);
        $target = "uploads/" . time() . "_" . $filename;
        move_uploaded_file($_FILES[$field]['tmp_name'], $target);
        return $target;
    }
    return "";
}

// Upload files
$passport_photo = uploadFile("passport_photo");
$id_front = uploadFile("id_front");
$id_back = uploadFile("id_back");
$collateral_doc = uploadFile("collateral_doc");

// Collect and sanitize form data
$data = array_map('htmlspecialchars', $_POST);

// Prepare SQL
$sql = "INSERT INTO loan_applications (
    full_name, last_name, id_number, nationality, phone,alt_phone ,email,
    loan_amount, repayment_period, town, location, landmark, ownership,
    business_name, years_operation, loan_purpose, repayment_plan,
    collateral, passport_photo, id_front, id_back, collateral_doc,
    application_date, spouse_name,spouse_phone, loan_taken_date, created_at
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?, CURRENT_TIMESTAMP())";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters
$stmt->bind_param(
    "ssssssdsssssssssssssssssss",
    $data['full_name'], $data['last_name'], $data['id_number'], $data['nationality'],
    $data['phone'], $data['alt_phone '] ,$data['email'], $data['loan_amount'], $data['repayment_period'],
    $data['town'], $data['location'], $data['landmark'], $data['ownership'],
    $data['business_name'], $data['years_operation'], $data['loan_purpose'], $data['repayment_plan'],
    $data['collateral'], $passport_photo, $id_front, $id_back, $collateral_doc,
    $data['application_date'], $data['spouse_name'],$data['spouse_phone'] ,$data['loan_taken_date']
);

// Execute
if ($stmt->execute()) {
    echo "<h2>Loan application submitted successfully.</h2>";
    echo "<a href='loan-form.php'>Submit another</a>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
