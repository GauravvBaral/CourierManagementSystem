<?php
// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connect to the database
$db = new mysqli('localhost', 'root', '', 'courier');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Delete order
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $stmt = $db->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $id);
    $_SESSION['msg'] = $stmt->execute() ? "Order deleted successfully!" : "Error deleting order: " . $stmt->error;
    header('Location: editbooking.php');
    exit();
}

// Update order
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $customer_email = trim($_POST['customer_email']);
    $toname = trim($_POST['toname']);
    $toaddress = trim($_POST['toaddress']);
    $tophone = trim($_POST['tophone']);
    $weight = trim($_POST['weight']);
    $status = trim($_POST['status']);

    if (!$name || !$address || !$phone || !$customer_email || !$toname || !$toaddress || !$tophone || !$weight || !$status) {
        $_SESSION['msg'] = "All fields are required.";
        header('Location: editbooking.php');
        exit();
    }

    // Get current status
    $stmt = $db->prepare("SELECT delivery_received_status, delivery_delivered_status FROM orders WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($delivery_received_status, $delivery_delivered_status);
    $stmt->fetch();
    $stmt->close();

    // Get the current employee name from session
    $emp_name = $_SESSION['username'] ?? null;

    // Update timestamps and employee names
    $received_by_emp_name = ($status === "received") ? $emp_name : null;
    $delivered_by_emp_name = ($status === "delivered") ? $emp_name : null;

    // Update the order in the database
    $stmt = $db->prepare("UPDATE orders SET 
        name = ?, address = ?, phone = ?, customer_email = ?, toname = ?, toaddress = ?, tophone = ?, 
        weight = ?, status = ?, received_by_emp_name = ?, delivered_by_emp_name = ?, 
        received_time = IF(status = 'received', NOW(), received_time), 
        delivery_time = IF(status = 'delivered', NOW(), delivery_time)
        WHERE id = ?");

    $stmt->bind_param("ssssssssssssi", 
        $name, $address, $phone, $customer_email, $toname, $toaddress, $tophone,
        $weight, $status, $received_by_emp_name, $delivered_by_emp_name, $id);

    $_SESSION['msg'] = $stmt->execute() ? "Order updated successfully!" : "Error updating order: " . $stmt->error;
    header('Location: editbooking.php');
    exit();
}
?>
