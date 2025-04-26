<?php
// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";

// Connect to the courier database
$courier = new mysqli($host, $username, $password, "courier");
if ($courier->connect_error) {
    die("Connection failed to courier database: " . $courier->connect_error);
}

// Query to count customers in the courier database
$customerCountQuery = "SELECT COUNT(*) AS total_customers FROM customers";
$customerResult = $courier->query($customerCountQuery);
$customerCount = $customerResult->fetch_assoc()["total_customers"] ?? 0;

// Query to count employees in the courier database
$employeeCountQuery = "SELECT COUNT(*) AS total_employees FROM employee";
$employeeResult = $courier->query($employeeCountQuery);
$employeeCount = $employeeResult->fetch_assoc()["total_employees"] ?? 0;

// Query to count orders in the courier database
$orderCountQuery = "SELECT COUNT(*) AS total_orders FROM orders";
$orderResult = $courier->query($orderCountQuery);
$orderCount = $orderResult->fetch_assoc()["total_orders"] ?? 0;

// Query to count approved orders in the courier database
$approvedOrderQuery = "SELECT COUNT(*) AS approved_orders FROM orders WHERE status = 'approved'";
$approvedResult = $courier->query($approvedOrderQuery);
$approvedOrders = $approvedResult->fetch_assoc()["approved_orders"] ?? 0;

$receivedOrderQuery = "SELECT COUNT(*) AS received_orders FROM orders WHERE delivery_received_status = 'received'";
$receivedResult = $courier->query($receivedOrderQuery);
$receivedOrders = $receivedResult->fetch_assoc()["received_orders"] ?? 0;

$deliveredOrderQuery = "SELECT COUNT(*) AS delivered_orders FROM orders WHERE delivery_delivered_status = 'delivered'";
$deliveredResult = $courier->query($deliveredOrderQuery);
$deliveredOrders = $deliveredResult->fetch_assoc()["delivered_orders"] ?? 0;

// Close database connection
$courier->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="CSS/admindash.css">
</head>

<body>
    <div class="dashboard">
        <h1>Admin Dashboard</h1>
        <div class="stats">
        <div class="stat">
                <button onclick="location.href='employee.html'">Add Employee</button>
            </div>
            <div class="stat">
                <h2><?php echo $orderCount; ?></h2>
                <p style="font-size: 17px;">Orders</p>
                <button onclick="location.href='sales.php'">View Orders</button>
            </div>
            <div class="stat">
                <h2><?php echo $employeeCount; ?></h2>
                <p style="font-size: 17px;">Employees</p>
                <button onclick="location.href='employeeview.php'">View Employees</button>
            </div>
            <div class="stat">
                <h2><?php echo $customerCount; ?></h2>
                <p style="font-size: 17px;">Customers</p>
                <button onclick="location.href='customerview.php'">View Customer</button>
            </div>

            <div class="stat">
                <h2><?php echo $approvedOrders; ?></h2>
                <p style="font-size: 17px;">Approved Orders</p>
            </div>
            <div class="stat">
                <h2><?php echo $receivedOrders; ?></h2>
                <p style="font-size: 17px;">Received Orders</p>
            </div>
            <div class="stat">
                <h2><?php echo $deliveredOrders; ?></h2>
                <p style="font-size: 17px;">Delivered Orders</p>
            </div>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <button onclick="window.location.href='index.html'" class="hover-button">
                LogOut
            </button>
</body>

</html>
