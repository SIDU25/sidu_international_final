<?php
// Database connection setup
$host = "localhost";
$user = "root";
$password = "";
$database = "sidu_db";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Receive form data
$chama_name = $_POST['chama_name'];
$chairman = $_POST['chairman'];
$treasurer = $_POST['treasurer'];
$secretary = $_POST['secretary'];
$members = $_POST['members'];

// Insert into chama_members table
$sql = "INSERT INTO chama_members (chama_name, chairman, treasurer, secretary, members)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $chama_name, $chairman, $treasurer, $secretary, $members);

if ($stmt->execute()) {
  echo "<h2>Chama Registered Successfully!</h2>";
  echo "<a href='chama.html'>Back to Chama Page</a>";
} else {
  echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>