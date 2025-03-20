<?php
session_start();
include 'db_connection.php'; // Ensure this file correctly establishes the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['email'])) {
        echo "Unauthorized";
        exit;
    }

    $order_id = $_POST['order_id'];

    // Check if the order belongs to the logged-in user
    $stmt = $conn->prepare("SELECT customer_email FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        echo "Order not found";
        exit;
    }
    $stmt->bind_result($customer_email);
    $stmt->fetch();
    
    // Ensure the logged-in user owns this order
    if ($customer_email !== $_SESSION['email']) {
        echo "Unauthorized action";
        exit;
    }
    
    // Update the order status to 'Cancelled'
    $updateStmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
    $updateStmt->bind_param("i", $order_id);

    if ($updateStmt->execute()) {
        echo "success";
    } else {
        echo "Error updating order";
    }

    $updateStmt->close();
    $stmt->close();
    $conn->close();
}
?>
