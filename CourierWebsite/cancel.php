<?php
session_start();
include 'db_connection.php'; // Ensure this correctly establishes $conn

// Ensure the request method is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Invalid request method");
}

// Ensure the user is logged in
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    exit("Unauthorized");
}

$customer_email = $_SESSION['email'];

// Validate and sanitize order ID
if (!isset($_POST['order_id']) || !ctype_digit($_POST['order_id'])) {
    exit("Invalid order ID");
}

$order_id = (int) $_POST['order_id']; // Convert to integer safely

// Fetch the order's current status and owner
$stmt = $conn->prepare("SELECT customer_email, status FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    exit("Order not found");
}

$stmt->bind_result($order_customer_email, $order_status);
$stmt->fetch();
$stmt->close();

// Ensure the order belongs to the logged-in user
if ($order_customer_email !== $customer_email) {
    exit("Unauthorized action");
}

// Ensure the order is NOT already cancelled
if (strtolower(trim($order_status)) === "cancelled") {
    exit("Order already cancelled");
}

// Update the order status to 'cancelled'
$updateStmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
$updateStmt->bind_param("i", $order_id);

if ($updateStmt->execute()) {
    echo "success";
} else {
    echo "Error updating order";
}

$updateStmt->close();
$conn->close();
?>
