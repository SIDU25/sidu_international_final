<?php
$host = "localhost";
$user = "root";          // Change if you use a different username
$password = "";          // Change if you have a password set
$database = "sidu_portal";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
