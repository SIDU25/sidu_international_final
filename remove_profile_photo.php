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

try {
    // Get current photo path
    $stmt = $conn->prepare("SELECT passport_photo FROM loan_applications WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentData = $result->fetch_assoc();
    
    if ($currentData && !empty($currentData['passport_photo'])) {
        $currentPhotoPath = $currentData['passport_photo'];
        
        // Update database to remove photo reference
        $stmt = $conn->prepare("UPDATE loan_applications SET passport_photo = '' WHERE email = ?");
        $stmt->bind_param("s", $email);
        
        if ($stmt->execute()) {
            // Delete the physical file if it exists
            if (file_exists($currentPhotoPath)) {
                unlink($currentPhotoPath);
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Profile photo removed successfully'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update database']);
        }
    } else {
        echo json_encode(['success' => true, 'message' => 'No profile photo to remove']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>