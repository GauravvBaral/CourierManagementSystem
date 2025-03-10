<?php
// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";
$database = "courier";

// Connect to the courier database
$courier = new mysqli($host, $username, $password, $database);
if ($courier->connect_error) {
    die("Connection failed to courier database: " . $courier->connect_error);
}

// Get the customer's email from the query parameter
$email = $_GET['email'] ?? '';

if (empty($email)) {
    die("Error: No email provided. Please provide a valid email address.");
}

// Fetch orders for the logged-in customer using their email
$orderQuery = "SELECT * FROM orders WHERE customer_email = ?";
$stmt = $courier->prepare($orderQuery);
$stmt->bind_param("s", $email);
$stmt->execute();
$orderResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link rel="stylesheet" href="CSS/orderview.css">
</head>

<body>
    <div class="dashboard">
        <h1>Your Orders</h1>
        <table>
            <thead>
                <tr>
                    <th>Sender's Name</th>
                    <th>Sender's Address</th>
                    <th>Sender's Phone</th>
                    <th>Receiver's Name</th>
                    <th>Receiver's Address</th>
                    <th>Receiver's Phone</th>
                    <th>Weight</th>
                    <th>Order Time</th>
                    <th>Status</th>
                    <th>Parcel Received Status</th>
                    <th>Parcel Delivered Status</th>
                    <th>Received by</th>
                    <th>Received Time</th>
                    <th>Delivered by</th>
                    <th>Delivery Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($orderResult->num_rows > 0): ?>
                    <?php while ($row = $orderResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['toname']); ?></td>
                            <td><?php echo htmlspecialchars($row['toaddress']); ?></td>
                            <td><?php echo htmlspecialchars($row['tophone']); ?></td>
                            <td><?php echo htmlspecialchars($row['weight']); ?></td>
                            <td><?php echo htmlspecialchars($row['time']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['delivery_received_status']); ?></td>
                            <td><?php echo htmlspecialchars($row['delivery_delivered_status']); ?></td>
                            <td><?php echo htmlspecialchars($row['received_by_emp_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['received_time']); ?></td>
                            <td><?php echo htmlspecialchars($row['delivered_by_emp_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['delivery_time']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No orders found for this customer.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="hover-button">
        <button onclick="window.location.href='customerdash.php'">Go Back</button>
    </div>
</body>

</html>

<?php
// Close statement and database connection
$stmt->close();
$courier->close();
?>
