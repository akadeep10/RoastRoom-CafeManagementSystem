<?php
$host = 'localhost';
$username = 'root';  // Using root as the MySQL username
$password = '';      // Leave this empty if root has no password
$dbname = 'cafe';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
