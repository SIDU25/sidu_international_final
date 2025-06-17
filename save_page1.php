<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "sidu_loan_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create uploads directory if it doesn't exist
$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Function to handle file uploads
function uploadFile($inputName, $uploadDir) {
    if (!empty($_FILES[$inputName]["name"])) {
        $filename = time() . "_" . basename($_FILES[$inputName]["name"]);
        $targetFile = $uploadDir . $filename;
        move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFile);
        return $targetFile;
    }
    return null;
}

// Get inputs
$applicant_name     = $_POST["applicant_name"];
$id_number          = $_POST["id_number"];
$nationality        = $_POST["nationality"];
$town               = $_POST["town"];
$phone              = $_POST["phone"];
$alt_phone          = $_POST["alt_phone"];
$email              = $_POST["email"];
$marital_status     = $_POST["marital_status"];
$spouse_name        = $_POST["spouse_name"];
$spouse_id          = $_POST["spouse_id"];
$spouse_phone       = $_POST["spouse_phone"];
$spouse_alt_phone   = $_POST["spouse_alt_phone"];

// Upload files
$passport_photo = uploadFile("passport_photo", $uploadDir);
$id_front       = uploadFile("id_front", $uploadDir);
$id_back        = uploadFile("id_back", $uploadDir);

// Insert into database
$sql = "INSERT INTO loan_applications (
    applicant_name, id_number, nationality, town,
    phone, alt_phone, email, marital_status,
    spouse_name, spouse_id, spouse_phone, spouse_alt_phone,
    passport_photo, id_front, id_back
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssssssssssss",
    $applicant_name, $id_number, $nationality, $town,
    $phone, $alt_phone, $email, $marital_status,
    $spouse_name, $spouse_id, $spouse_phone, $spouse_alt_phone,
    $passport_photo, $id_front, $id_back
);

if ($stmt->execute()) {
    echo "<script>alert('Page 1 saved! You can proceed to Page 2.'); window.location.href='loan_page2.php';</script>";
} else {
    echo "Error saving record: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
