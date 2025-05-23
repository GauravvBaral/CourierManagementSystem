<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('editfinalorder.php');

$results = mysqli_query($db, "SELECT * FROM orders");
if (!$results) {
    die("Query failed: " . mysqli_error($db));
}

if (isset($_GET['edit']) && isset($_GET['action'])) {
    $id = intval($_GET['edit']);
    $action = $_GET['action'];

    $stmt = $db->prepare("SELECT status, delivery_received_status, delivery_delivered_status FROM orders WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($status, $delivery_received_status, $delivery_delivered_status);
    $stmt->fetch();
    $stmt->close();

    $emp_name_from_session = $_SESSION['username'] ?? 'Unknown Employee';

    if ($status === 'pending') {
        $_SESSION['msg'] = "Cannot receive or deliver an order with 'pending' status.";
    } elseif ($status === 'declined') {
        $_SESSION['msg'] = "Can't receive or deliver if the order is declined.";
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
                $_SESSION['msg'] = $stmt->execute() ? "Order received successfully!" : "Error updating order: " . $stmt->error;
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
                $_SESSION['msg'] = $stmt->execute() ? "Order delivered successfully!" : "Error updating order: " . $stmt->error;
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
    <script>
        // Auto-hide the session message after 1 minute
        window.onload = function () {
            const msgDiv = document.getElementById("sessionMessage");
            if (msgDiv) {
                setTimeout(() => {
                    msgDiv.style.display = "none";
                }, 30000); // 30 seconds
            }
        };
    </script>
</head>
<body>

<?php if (isset($_SESSION['msg'])): ?>
    <div id="sessionMessage" class="msg">
        <?= $_SESSION['msg']; unset($_SESSION['msg']); ?>
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
                <td><?= htmlspecialchars($row['customer_name']); ?></td>
                <td><?= htmlspecialchars($row['customer_address']); ?></td>
                <td><?= htmlspecialchars($row['customer_phone']); ?></td>
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
                    <?php if ($row['status'] !== 'pending' && $row['status'] !== 'declined' && $row['status'] !== 'cancelled' && $row['delivery_received_status'] !== 'received'): ?>
                        <button onclick="window.location.href='editbooking.php?edit=<?= $row['id']; ?>&action=receive';" class="edit_btn">Receive</button>
                    <?php else: ?>
                        <button class="edit_btn" disabled style="color: black; background-color: grey; cursor: not-allowed;">Receive Disabled</button>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($row['status'] !== 'pending' && $row['status'] !== 'declined' && $row['status'] !== 'cancelled' && $row['delivery_received_status'] === 'received' && $row['delivery_delivered_status'] !== 'delivered'): ?>
                        <button onclick="window.location.href='editbooking.php?edit=<?= $row['id']; ?>&action=deliver';" class="edit_btn2">Deliver</button>
                    <?php else: ?>
                        <button class="edit_btn2" disabled style="color: black; background-color: grey; cursor: not-allowed;">Delivery Disabled</button>
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
