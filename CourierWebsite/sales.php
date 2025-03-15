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
        <div class="msg">
            <?php
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
            ?>
        </div>
    <?php endif ?>

    <h1>WELCOME ADMINISTRATOR!</h1>

    <table>
        <caption style="font-size: 19px;"><b>ORDERED COURIERS</b></caption>
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
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
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
                        <a class="approve_btn" href="config.php?approve=<?php echo $row['id']; ?>">Approve</a>
                    </td>
                    <td>
                        <a class="deny_btn" href="config.php?denied=<?php echo $row['id']; ?>">Deny</a>
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

</body>

</html>
