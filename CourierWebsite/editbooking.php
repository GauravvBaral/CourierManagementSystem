<?php 
// Start the session if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('editfinalorder.php'); // Ensure database connection is established

// Fetch all records from the database
$results = mysqli_query($db, "SELECT * FROM orders");
if (!$results) {
    die("Query failed: " . mysqli_error($db));
}

// Handle status updates for Receive and Deliver
if (isset($_GET['edit']) && isset($_GET['action'])) {
    $id = intval($_GET['edit']);
    $action = $_GET['action'];

    // Fetch current order details
    $stmt = $db->prepare("SELECT status, delivery_received_status, delivery_delivered_status FROM orders WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($status, $delivery_received_status, $delivery_delivered_status);
    $stmt->fetch();
    $stmt->close();

    // Get employee's username from session
    $emp_name_from_session = $_SESSION['username'] ?? null;

    if ($status === 'pending') {
        $_SESSION['msg'] = "Cannot receive or deliver an order with 'pending' status.";
    } else {
        if ($action === 'receive') {
            if ($delivery_received_status === 'received') {
                $_SESSION['msg'] = "Order already received. Can't receive more than once.";
            } else {
                $stmt = $db->prepare("UPDATE orders SET 
                    delivery_received_status = 'received', 
                    received_time = NOW(),  
                    received_by_emp_name = ? 
                    WHERE id = ?");
                $stmt->bind_param("si", $emp_name_from_session, $id);
                if ($stmt->execute()) {
                    $_SESSION['msg'] = "Order received successfully!";
                } else {
                    $_SESSION['msg'] = "Error updating order: " . $stmt->error;
                }
                $stmt->close();
            }
        } elseif ($action === 'deliver') {
            if ($delivery_delivered_status === 'delivered') {
                $_SESSION['msg'] = "Order already delivered. Can't deliver more than once.";
            } else {
                $stmt = $db->prepare("UPDATE orders SET 
                    delivery_delivered_status = 'delivered', 
                    delivery_time = NOW(), 
                    delivered_by_emp_name = ? 
                    WHERE id = ?");
                $stmt->bind_param("si", $emp_name_from_session, $id);
                if ($stmt->execute()) {
                    $_SESSION['msg'] = "Order delivered successfully!";
                } else {
                    $_SESSION['msg'] = "Error updating order: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }

    header('Location: editbooking.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Orders Page</title>
    <link rel="stylesheet" type="text/css" href="CSS/edit.css">
</head>
<body>
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="msg">
            <?php
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
            ?>
        </div>
    <?php endif ?>

    <h2 style="text-align: center; background-color: darkgreen; color: yellow; padding: 30px;">Edit Booking Orders!</h2>
    
    <table>
        <thead>
            <tr>
                <th>Sender's Name</th>
                <th>Sender's Address</th>
                <th>Sender's Contact</th>
                <th>Sender's Email</th>
                <th>Receiver's Name</th>
                <th>Receiver's Address</th>
                <th>Receiver's Number</th>
                <th>Package Weight</th>
                <th>Order Time</th>
                <th>Status</th>
                <th>Parcel-Receive-Status</th>
                <th>Parcel-Deliver-Status</th>
                <th>Received By</th>
                <th>Delivered By</th>
                <th>Received Time</th>
                <th>Delivery Time</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($results)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['address']); ?></td>
                    <td><?= htmlspecialchars($row['phone']); ?></td>
                    <td><?= htmlspecialchars($row['customer_email']); ?></td>
                    <td><?= htmlspecialchars($row['toname']); ?></td>
                    <td><?= htmlspecialchars($row['toaddress']); ?></td>
                    <td><?= htmlspecialchars($row['tophone']); ?></td>
                    <td><?= htmlspecialchars($row['weight']); ?></td>
                    <td><?= htmlspecialchars($row['time']); ?></td>
                    <td><?= htmlspecialchars($row['status']); ?></td>
                    <td><?= htmlspecialchars($row['delivery_received_status']); ?></td>
                    <td><?= htmlspecialchars($row['delivery_delivered_status']); ?></td>
                    <td><?= htmlspecialchars($row['received_by_emp_name']); ?></td>
                    <td><?= htmlspecialchars($row['delivered_by_emp_name']); ?></td>
                    <td><?= htmlspecialchars($row['received_time']); ?></td>
                    <td><?= htmlspecialchars($row['delivery_time']); ?></td>
                    <td>
                        <?php if ($row['status'] !== 'pending'): ?>
                            <a class="edit_btn" href="editbooking.php?edit=<?= $row['id']; ?>&action=receive">Receive</a>
                        <?php else: ?>
                            <span style="color: gray;">Not Allowed</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['status'] !== 'pending'): ?>
                            <a class="edit_btn2" href="editbooking.php?edit=<?= $row['id']; ?>&action=deliver">Deliver</a>
                        <?php else: ?>
                            <span style="color: gray;">Not Allowed</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div style="text-align: center; margin-top: 20px;">
        <button onclick="window.location.href='index.html'" class="hover-button">Log Out</button>
    </div>
</body>
</html>


