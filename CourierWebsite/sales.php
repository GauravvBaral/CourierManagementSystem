<?php
include('config.php');

$results = mysqli_query($db, "SELECT * FROM orders");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Total Sales Page</title>
    <link rel="stylesheet" type="text/css" href="CSS/sales.css">
</head>

<body>
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="msg" id="sessionMessage">
            <?php
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
            ?>
        </div>
    <?php endif ?>

    <h1>WELCOME ADMINISTRATOR!</h1>

    <table>
        <caption style="font-size: 22px;">ORDERED COURIERS</caption>
        <thead>
            <tr>
                <th>Sender's Name</th>
                <th>Sender's Address</th>
                <th>Sender's Contact</th>
                <th>Sender's Email</th>
                <th>Receiver's Name</th>
                <th>Receiver's Address</th>
                <th>Receiver's Contact</th>
                <th> Weight (kg)</th>
                <th>Order Time</th>
                <th>Status</th>
                <th>Parcel Receive Status</th>
                <th>Parcel Deliver Status</th>
                <th>Receive Time</th>
                <th>Delivery Time</th>
                <th colspan="3" style="padding: 0 20px;">Actions</th>
            </tr>
        </thead>
        <tbody>
    <?php while ($row = mysqli_fetch_array($results)) { ?>
        <tr>
            <td><?php echo $row['customer_name']; ?></td>
            <td><?php echo $row['customer_address']; ?></td>
            <td><?php echo $row['customer_phone']; ?></td>
            <td><?php echo $row['customer_email']; ?></td>
            <td><?php echo $row['toname']; ?></td>
            <td><?php echo $row['toaddress']; ?></td>
            <td><?php echo $row['tophone']; ?></td>
            <td><?php echo $row['weight']; ?></td>
            <td><?php echo $row['time']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['delivery_received_status']; ?></td>
            <td><?php echo $row['delivery_delivered_status']; ?></td>
            <td><?php echo $row['received_time']; ?></td>
            <td><?php echo $row['delivery_time']; ?></td>
            <td>
                <?php if ($row['status'] !== "cancelled"): ?>
                    <a class="approve_btn" href="config.php?approve=<?php echo $row['id']; ?>">Approve</a>
                <?php else: ?>
                    <button class="approve_btn" disabled>Approve</button>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($row['status'] !== "cancelled" && $row['delivery_received_status'] !== "received"): ?>
                    <a class="deny_btn" href="config.php?declined=<?php echo $row['id']; ?>">Decline</a>
                <?php else: ?>
                    <button class="deny_btn" disabled>Decline</button>
                <?php endif; ?>
            </td>
            <td>
                <a class="del_btn" href="config.php?del=<?php echo $row['id']; ?>">Delete</a>
            </td>
        </tr>
    <?php } ?>
</tbody>
    </table>
    <div style="text-align: center; margin-top: 20px;">
        <button onclick="window.location.href='admindash.php'" class="hover-button">
            Go Back
        </button>
    </div>

    <script type="text/javascript">
        // Check if there's a session message and if it exists, set a timeout to remove it
        window.onload = function() {
            var msgElement = document.getElementById("sessionMessage");
            if (msgElement) {
                setTimeout(function() {
                    msgElement.style.display = "none";  // Hide the message after 1 minute
                }, 30000);  // 30000 milliseconds = 30 secs
            }
        };
    </script>

</body>
</html>
