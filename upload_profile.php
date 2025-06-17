<?php
session_start();

// Set content type for JSON response
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['id_number'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "sidu_portal");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

$email = $_SESSION['id_number'];

// Check if file was uploaded
if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit();
}

$file = $_FILES['photo'];

// Validate file type
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$fileType = $file['type'];

if (!in_array($fileType, $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed']);
    exit();
}

// Validate file size (max 5MB)
$maxSize = 5 * 1024 * 1024; // 5MB
if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'File size too large. Maximum size is 5MB']);
    exit();
}

// Create upload directory if it doesn't exist
$uploadDir = 'uploads/profile_photos/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        echo json_encode(['success' => false, 'message' => 'Failed to create upload directory']);
        exit();
    }
}

// Generate unique filename
$fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
$fileName = $email . '_' . time() . '.' . $fileExtension;
$targetPath = $uploadDir . $fileName;

// Get current photo to delete old one
$stmt = $conn->prepare("SELECT passport_photo FROM loan_applications WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$currentData = $result->fetch_assoc();

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    // Update database
    $stmt = $conn->prepare("UPDATE loan_applications SET passport_photo = ? WHERE email = ?");
    $stmt->bind_param("ss", $targetPath, $email);
    
    if ($stmt->execute()) {
        // Delete old photo file if it exists and is not the default
        if ($currentData && !empty($currentData['passport_photo']) && 
            file_exists($currentData['passport_photo']) && 
            $currentData['passport_photo'] !== $targetPath) {
            unlink($currentData['passport_photo']);
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Profile photo updated successfully',
            'photo_url' => $targetPath
        ]);
    } else {
        // Delete uploaded file if database update failed
        unlink($targetPath);
        echo json_encode(['success' => false, 'message' => 'Failed to update database']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
}

$stmt->close();
$conn->close();
?>