<?php
session_start();
include('emplogin.php'); // Include the database connection file

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Fetch employee data
    $query = "SELECT * FROM employee WHERE email='$email'";
    $result = mysqli_query($conn, $query) or die("Could not execute query: " . mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);

    // Verify password
    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['username'] = $row['username']; // Set session variable for username
        $_SESSION['password'] = $password; // Set session variable for password
        header('Location: editbooking.php'); // Redirect to employee dashboard
        exit();
    } else {
        header("Location: employeelog.php?error=invalid_credentials"); // Redirect with error
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Login</title>
    <link rel="stylesheet" href="CSS/employeelog.css">
    <style>
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <form method="post" action="employeelog.php" id="Login">
        <div class="login-box">
            <h1>Login</h1>
            <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_credentials') { ?>
                <div class="error">Invalid email or password!</div>
            <?php } ?>
            <div class="textbox">
                <i class="fa fa-user" aria-hidden="true"></i>
                <input type="email" placeholder="Email" name="email" required>
            </div>
            <div class="textbox">
                <i class="fa fa-lock" aria-hidden="true"></i>
                <input type="password" placeholder="Password" name="password" required>
            </div>
            <div>
                <tr>
                    <td align="center" colspan="2">
                        <div class="login-section">
                            <a href="employee.html">
                                <span style="color: blue;">Sign Up/Register</span>
                            </a>
                        </div>
                    </td>
                </tr>
            </div>
            <div>
                <input class="btn" type="submit" name="" value="Sign in">
            </div>
        </div>
    </form>
    <a href="index.html" class="button1">
        <input type="button" value="GO BACK" class="button">
    </a>
</body>
</html>