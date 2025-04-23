<?php
session_start();
include('custlogin.php');

if (isset($_SESSION['email'])) {
    header('Location: customerdash.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM customers WHERE email='$email'";
    $result = mysqli_query($conn, $query) or die("Could not execute query: " . mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['email'] = $email;
        header('Location: customerdash.php');
        exit();
    } else {
        header("Location: customerlog.php?error=invalid_credentials");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Customer Login</title>
    <link rel="stylesheet" href="CSS/customerlog.css">
</head>
<body>
    <div class="login-box">
        <h1>Customer Login</h1>
        <?php if (isset($_GET['error'])): ?>
            <p style="color:red;">Invalid email or password</p>
        <?php endif; ?>
        <form method="post" action="customerlog.php">
            <div class="textbox">
                <input type="email" placeholder="Email" name="email" required>
            </div>
            <div class="textbox">
                <input type="password" placeholder="Password" name="password" required>
            </div>
            <input class="btn" type="submit" value="Log in">
        </form>
        <div class="login-section">
            <a href="customer.html" style="color: blue;">Sign up / Register</a>
        </div>
    </div>
    <div class="goback-container">
        <a href="index.php" class="goback">Go Back</a>
    </div>
</body>
</html>
