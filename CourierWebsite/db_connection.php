<?php
$host = "localhost";   // Database host (usually 'localhost')
$username = "root";    // Database username
$password = "";        // Database password (leave empty if no password is set)
$database = "courier"; // Database name

// Create a new MySQLi connection
$conn = new mysqli($host, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
