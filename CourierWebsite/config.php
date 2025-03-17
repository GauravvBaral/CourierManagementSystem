<?php
session_start(); 

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

$db = mysqli_connect('localhost', 'root', '', 'courier');
if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']); 

    $check_query = "SELECT status FROM orders WHERE id = $id";
    $result = mysqli_query($db, $check_query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $current_status = $row['status'];

        if ($current_status == 'Approved') {
            $_SESSION['msg'] = "Order already approved, can't approve more.";
        } else {
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

    header('location: sales.php');
    exit();
}

if (isset($_GET['declined'])) {
    $id = intval($_GET['declined']); 

    $check_query = "SELECT status FROM orders WHERE id = $id";
    $result = mysqli_query($db, $check_query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $current_status = $row['status'];

        if ($current_status == 'declined') {
            $_SESSION['msg'] = "Order already declied.";
        } else {
            $update_query = "UPDATE orders SET status='Declined' WHERE id = $id";
            if (mysqli_query($db, $update_query)) {
                $_SESSION['msg'] = "Order declined successfully.";
            } else {
                $_SESSION['msg'] = "Error declinig order: " . mysqli_error($db);
            }
        }
    } else {
        $_SESSION['msg'] = "Order not found.";
    }

    header('location: sales.php');
    exit();
}

if (isset($_POST['save'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $toname = mysqli_real_escape_string($db, $_POST['toname']);
    $toaddress = mysqli_real_escape_string($db, $_POST['toaddress']);
    $tophone = mysqli_real_escape_string($db, $_POST['tophone']);
    $weight = mysqli_real_escape_string($db, $_POST['weight']);
    $status = isset($_POST['status']) ? mysqli_real_escape_string($db, $_POST['status']) : 'Pending';
    $time = date('Y-m-d H:i:s');
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

if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $toname = mysqli_real_escape_string($db, $_POST['toname']);
    $toaddress = mysqli_real_escape_string($db, $_POST['toaddress']);
    $tophone = mysqli_real_escape_string($db, $_POST['tophone']);
    $weight = mysqli_real_escape_string($db, $_POST['weight']);
    $status = mysqli_real_escape_string($db, $_POST['status']);
    $id = intval($_POST['id']);
    $time = date('Y-m-d H:i:s');

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

if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    if (mysqli_query($db, "DELETE FROM orders WHERE id=$id")) {
        $_SESSION['msg'] = "Customer Data Deleted Successfully";
    } else {
        $_SESSION['msg'] = "Error Deleting Customer Data: " . mysqli_error($db);
    }
    header('location: sales.php');
    exit();
}

$results = mysqli_query($db, "SELECT * FROM orders");
?>
