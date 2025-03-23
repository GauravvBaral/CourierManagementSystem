<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "courier";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $query = "DELETE FROM customers WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Customer deleted successfully.";
    } else {
        echo "Error deleting customer: " . $conn->error;
    }
    $stmt->close();
}

$conn->close();
header("Location: customerview.php");
exit();
?>