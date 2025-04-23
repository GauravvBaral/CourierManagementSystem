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

// Start session to track customer email
session_start();
$email = $_SESSION['email'] ?? '';  // Assuming the email is stored in session

// Initialize customer_id variable
$customer_id = 0;

// Fetch customer_id using email from the orders table
if ($email != '') {
    $orderQuery = "SELECT customer_id FROM orders WHERE customer_email = ? LIMIT 1";  // Assuming customer_email exists in the orders table
    $stmt = $courier->prepare($orderQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $orderResult = $stmt->get_result();

    // Fetch the customer ID from the first order
    if ($orderResult->num_rows > 0) {
        $orderRow = $orderResult->fetch_assoc();
        $customer_id = $orderRow['customer_id'];
        // echo "Debug: Customer ID from orders table = " . $customer_id . "<br>";
    } else {
        // echo "Error: No orders found for this customer!<br>";
    }
} else {
    echo "Error: No email found in session.<br>";
}

// Initialize variables for order counts
$orderCount = 0;
$approvedOrders = 0;
$receivedOrders = 0;
$deliveredOrders = 0;

// Query to get total orders
$orderCountQuery = "SELECT COUNT(*) AS total_orders FROM orders WHERE customer_id = ?";
$stmt = $courier->prepare($orderCountQuery);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$orderResult = $stmt->get_result();
$orderRow = $orderResult->fetch_assoc();

// Check if the query was successful and fetch result
if ($orderRow && isset($orderRow["total_orders"])) {
    $orderCount = $orderRow["total_orders"];
} else {
    echo "Error: Query failed or no orders found!<br>";
}

// Query to count approved orders for the customer
$approvedOrderQuery = "SELECT COUNT(*) AS approved_orders FROM orders WHERE customer_id = ? AND status = 'approved'";
$stmt = $courier->prepare($approvedOrderQuery);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$approvedResult = $stmt->get_result();
$approvedRow = $approvedResult->fetch_assoc();

// Check if the query was successful and fetch result
if ($approvedRow && isset($approvedRow["approved_orders"])) {
    $approvedOrders = $approvedRow["approved_orders"];
} else {
    echo "Error: Query failed or no approved orders found!<br>";
}

// Query to count received orders for the customer
$receivedOrderQuery = "SELECT COUNT(*) AS received_orders FROM orders WHERE customer_id = ? AND delivery_received_status = 'received'";
$stmt = $courier->prepare($receivedOrderQuery);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$receivedResult = $stmt->get_result();
$receivedRow = $receivedResult->fetch_assoc();

// Check if the query was successful and fetch result
if ($receivedRow && isset($receivedRow["received_orders"])) {
    $receivedOrders = $receivedRow["received_orders"];
} else {
    echo "Error: Query failed or no received orders found!<br>";
}

// Query to count delivered orders for the customer
$deliveredOrderQuery = "SELECT COUNT(*) AS delivered_orders FROM orders WHERE customer_id = ? AND delivery_delivered_status = 'delivered'";
$stmt = $courier->prepare($deliveredOrderQuery);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$deliveredResult = $stmt->get_result();
$deliveredRow = $deliveredResult->fetch_assoc();

// Check if the query was successful and fetch result
if ($deliveredRow && isset($deliveredRow["delivered_orders"])) {
    $deliveredOrders = $deliveredRow["delivered_orders"];
} else {
    echo "Error: Query failed or no delivered orders found!<br>";
}

// Close statement and database connection
$stmt->close();
$courier->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="CSS/customerdash.css">
</head>
<script>
    function confirmLogout() {
        const confirmed = confirm("Are you sure you want to logout?");
        if (confirmed) {
            window.location.href = 'logout.php';
        }
    }
</script>
<body>
    <div class="dashboard">
        <h1>Customer Dashboard</h1>
        <div class="stats">
            <div class="stat" id="button">
                <button onclick="window.location.href='booking.php'" style="font-size: 2em;">Book Order</button>
            </div>
            <div class="stat">
                <h2><?php echo $orderCount; ?></h2>
                <p>Your Orders</p>
                <button onclick="window.location.href='orderview.php?email=<?php echo $email; ?>'">View OrderDetails</button>
            </div>
            <div class="stat">
                <h2><?php echo $approvedOrders; ?></h2>
                <p>Approved Orders</p>
            </div>
            <div class="stat">
                <h2><?php echo $receivedOrders; ?></h2>
                <p>Received Orders</p>
            </div>
            <div class="stat">
                <h2><?php echo $deliveredOrders; ?></h2>
                <p>Delivered Orders</p>
            </div>
        </div>
    </div>
    <div style="text-align: center; margin-top: 20px;">
        <button onclick="window.location.href='index.html'" class="hover-button">Go-back</button>
    </div>
    <div>
        <button onclick="confirmLogout()" class="hover-button2">LogOut</button>
    </div>
</body>

</html>
