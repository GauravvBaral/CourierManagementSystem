<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connect to the database
$db = new mysqli('localhost', 'root', '', 'courier');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $stmt = $db->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $id);
    $_SESSION['msg'] = $stmt->execute() ? "Order deleted successfully!" : "Error deleting order: " . $stmt->error;
    $stmt->close();
    header('Location: editbooking.php');
    exit();
}

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $customer_email = trim($_POST['customer_email']);
    $toname = trim($_POST['toname']);
    $toaddress = trim($_POST['toaddress']);
    $tophone = trim($_POST['tophone']);
    $weight = trim($_POST['weight']);
    $status = trim($_POST['status']);

    if (empty($customer_name) || empty($customer_address) || empty($customer_phone) || empty($customer_email) ||
        empty($toname) || empty($toaddress) || empty($tophone) || empty($weight) || empty($status)) {
        $_SESSION['msg'] = "All fields are required.";
        header('Location: editbooking.php');
        exit();
    }

    $stmt = $db->prepare("SELECT status, delivery_received_status, delivery_delivered_status FROM orders WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($current_status, $delivery_received_status, $delivery_delivered_status);
    $stmt->fetch();
    $stmt->close();
    
    $emp_name = $_SESSION['username'] ?? null;

    $received_by_emp_name = ($status === "received") ? $emp_name : null;
    $delivered_by_emp_name = ($status === "delivered") ? $emp_name : null;

    $stmt = $db->prepare("UPDATE orders SET 
        customer_name = ?, customer_address = ?, customer_phone = ?, customer_email = ?, toname = ?, toaddress = ?, tophone = ?, 
        weight = ?, status = ?, received_by_emp_name = ?, delivered_by_emp_name = ?, 
        received_time = IF(? = 'received', NOW(), received_time), 
        delivery_time = IF(? = 'delivered', NOW(), delivery_time)
        WHERE id = ?");

    $stmt->bind_param("sssssssssssssi", 
        $customer_name, $customer_address, $phone, $customer_email, $toname, $toaddress, $tophone,
        $weight, $status, $received_by_emp_name, $delivered_by_emp_name, 
        $status, $status, $id);

    $_SESSION['msg'] = $stmt->execute() ? "Order updated successfully!" : "Error updating order: " . $stmt->error;
    $stmt->close();
    header('Location: editbooking.php');
    exit();
}
?>
