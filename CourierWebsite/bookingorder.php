<?php
session_start();
// Connect to database
$db = mysqli_connect('localhost', 'root', '', 'courier');

if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Ensure customer is logged in
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email']; // Get email from session
} else {
    die("Error: Customer not logged in.");
}

// Initialize variables
$name = "";
$address = "";
$phone = "";
$toname = "";
$toaddress = "";
$tophone = "";
$weight = "";
$edit_state = false; // Track if we are in edit mode

// If save button is clicked
if (isset($_POST['save'])) {
    $toname = mysqli_real_escape_string($db, $_POST['toname']);
    $toaddress = mysqli_real_escape_string($db, $_POST['toaddress']);
    $tophone = mysqli_real_escape_string($db, $_POST['tophone']);
    $weight = mysqli_real_escape_string($db, $_POST['weight']);

    // Validate phone numbers
    if ($tophone <= 0) {
        die("Error: Phone numbers must be greater than 0.");
    }

    // Get customer ID and email from database
    $result = mysqli_query($db, "SELECT id, email, username, address, phone FROM customers WHERE email = '$email'");
    if (mysqli_num_rows($result) > 0) {
        $customer = mysqli_fetch_assoc($result);
        $customer_id = $customer['id'];
        $customer_email = $customer['email'];
        $customer_name = $customer['username'];
        $customer_address = $customer['address'];
        $customer_phone = $customer['phone'];

        // Insert order with customer_id and customer_email
        $query = "INSERT INTO orders (customer_id, customer_email, customer_name,customer_address, customer_phone, toname, toaddress, tophone, weight, price) 
                  VALUES ('$customer_id', '$customer_email', '$customer_name', '$customer_address', '$customer_phone', '$toname', '$toaddress', '$tophone', '$weight', 0)";

        if (mysqli_query($db, $query)) {
            $_SESSION['msg'] = "ORDER PLACED SUCCESSFULLY!";
            header('location: booking.php'); // Redirect to booking page
            exit;
        } else {
            die("Error: " . mysqli_error($db));
        }
    } else {
        die("Error: Customer not found.");
    }
}

// If updating an order (edit mode)
if (isset($_POST['update'])) {
    $order_id = $_POST['order_id'];  // Assuming order_id is passed for update
    $name = mysqli_real_escape_string($db, $_POST['customer_name']);
    $address = mysqli_real_escape_string($db, $_POST['customer_address']);
    $phone = mysqli_real_escape_string($db, $_POST['customer_phone']);
    $toname = mysqli_real_escape_string($db, $_POST['toname']);
    $toaddress = mysqli_real_escape_string($db, $_POST['toaddress']);
    $tophone = mysqli_real_escape_string($db, $_POST['tophone']);
    $weight = mysqli_real_escape_string($db, $_POST['weight']);

    // Update query
    $update_query = "UPDATE orders SET 
                        name='$customer_name', 
                        address='$customer_address', 
                        phone='$customer_phone', 
                        toname='$toname', 
                        toaddress='$toaddress', 
                        tophone='$tophone', 
                        weight='$weight', 
                        customer_email='$customer_email' 
                     WHERE id='$order_id'";

    if (mysqli_query($db, $update_query)) {
        $_SESSION['msg'] = "Order updated successfully!";
        header('location: booking.php'); // Redirect to booking page
        exit;
    } else {
        die("Error: " . mysqli_error($db));
    }
}

// Retrieve records
$results = mysqli_query($db, "SELECT * FROM orders");
?>