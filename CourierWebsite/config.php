<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session only if it's not already active
}

// Initialize variables
$name = "";
$address = "";
$phone = "";
$toname = "";
$toaddress = "";
$tophone = "";
$weight = "";
$time = "";
$status = "Pending";
$delivery_received_status = "not received";
$delivery_delivered_status = "not delivered";
$id = 0;
$edit_state = false;

// Connect to database
$db = mysqli_connect('localhost', 'root', '', 'courier');
if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Approve the record and update the status
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']); // Ensure ID is an integer

    // Fetch the current status of the order
    $check_query = "SELECT status FROM orders WHERE id = $id";
    $result = mysqli_query($db, $check_query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $current_status = $row['status'];

        if ($current_status == 'approved') {
            // If already approved, set a session message
            $_SESSION['msg'] = "Order already approved, can't approve more.";
        } else {
            // If not approved, update the status
            $update_query = "UPDATE orders SET status='Approved' WHERE id = $id";
            if (mysqli_query($db, $update_query)) {
                $_SESSION['msg'] = "Order approved successfully.";
            } else {
                $_SESSION['msg'] = "Error approving order: " . mysqli_error($db);
            }
        }
    } else {
        $_SESSION['msg'] = "Order not found.";
    }

    header('location: sales.php'); // Redirect back to the sales page
    exit();
}

// Save new record
if (isset($_POST['save'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $toname = mysqli_real_escape_string($db, $_POST['toname']);
    $toaddress = mysqli_real_escape_string($db, $_POST['toaddress']);
    $tophone = mysqli_real_escape_string($db, $_POST['tophone']);
    $weight = mysqli_real_escape_string($db, $_POST['weight']);
    $status = isset($_POST['status']) ? mysqli_real_escape_string($db, $_POST['status']) : 'Pending';
    $time = date('Y-m-d H:i:s'); // Capture the current time
    $delivery_received_status = "not received";
    $delivery_delivered_status = "not delivered";
    $received_time = "";
    $delivery_time = "";

    $query = "INSERT INTO orders (name, address, phone, toname, toaddress, tophone, weight, status, time, delivery_received_status, delivery_delivered_status, received_time, delivery_time) 
              VALUES ('$name', '$address', '$phone', '$toname', '$toaddress', '$tophone', '$weight', '$status', '$time', '$delivery_received_status', '$delivery_delivered_status', '$received_time', '$delivery_time')";
    if (mysqli_query($db, $query)) {
        $_SESSION['msg'] = "Customer Data Saved Successfully";
    } else {
        $_SESSION['msg'] = "Error Saving Customer Data: " . mysqli_error($db);
    }
    header('location: sales.php');
    exit();
}

// Update record
if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $toname = mysqli_real_escape_string($db, $_POST['toname']);
    $toaddress = mysqli_real_escape_string($db, $_POST['toaddress']);
    $tophone = mysqli_real_escape_string($db, $_POST['tophone']);
    $weight = mysqli_real_escape_string($db, $_POST['weight']);
    $status = mysqli_real_escape_string($db, $_POST['status']);
    $id = intval($_POST['id']); // Ensure ID is an integer
    $time = date('Y-m-d H:i:s'); // Capture the current time for update

    $sql = "UPDATE orders 
            SET name='$name', address='$address', phone='$phone', 
                toname='$toname', toaddress='$toaddress', tophone='$tophone', 
                weight='$weight', status='$status', time='$time' 
            WHERE id = $id";
    if (mysqli_query($db, $sql)) {
        $_SESSION['msg'] = "Customer Data Updated Successfully";
    } else {
        $_SESSION['msg'] = "Error Updating Customer Data: " . mysqli_error($db);
    }
    header('location: sales.php');
    exit();
}

// Delete record
if (isset($_GET['del'])) {
    $id = intval($_GET['del']); // Ensure ID is an integer
    if (mysqli_query($db, "DELETE FROM orders WHERE id=$id")) {
        $_SESSION['msg'] = "Customer Data Deleted Successfully";
    } else {
        $_SESSION['msg'] = "Error Deleting Customer Data: " . mysqli_error($db);
    }
    header('location: sales.php');
    exit();
}

// Retrieve records
$results = mysqli_query($db, "SELECT * FROM orders");
?>