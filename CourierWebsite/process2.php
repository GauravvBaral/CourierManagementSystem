<!DOCTYPE html>
<html>
<head>
    <title>Employee Registration</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Century Gothic, sans-serif;
            background-image: url(ruby.jpg);
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: rgba(255, 255, 255, 0.4); /* Transparent background */
            border: 2px solid rgba(27, 45, 214, 0.8); /* Visible border */
            border-radius: 10px;
            width: 400px;
            padding: 30px;
            text-align: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* Optional shadow for depth */
        }

        .container h1 {
            font-size: 24px;
            color: rgb(34, 34, 34);
            margin-bottom: 20px;
        }

        .button1, .button2 {
            display: inline-block;
            background-color: rgb(27, 45, 214);
            color: white;
            font-size: 18px;
            padding: 12px 25px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 140px; /* Fixed button size */
            text-align: center;
        }

        .button1:hover, .button2:hover {
            background-color: rgb(64, 19, 201);
            transform: scale(1.05);
        }

        .error {
            color: red;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .success {
            color: green;
            font-size: 18px;
			font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
<?php
$message = ""; // Variable to store success or error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $education = htmlspecialchars($_POST['education']);
    $designation = htmlspecialchars($_POST['designation']);
    $address = htmlspecialchars($_POST['address']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Validate inputs
    if (empty($username) || empty($password) || empty($confirmPassword) || empty($education) || empty($designation) || empty($address) || empty($phone) || empty($email)) {
        $message = "<div class='error'>All fields are required!</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='error'>Invalid email format!</div>";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $message = "<div class='error'>Phone number must be 10 digits!</div>";
    } elseif (strlen($password) < 8) {
        $message = "<div class='error'>Password must be at least 8 characters long!</div>";
    } elseif ($password !== $confirmPassword) {
        $message = "<div class='error'>Passwords do not match!</div>";
    } else {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Database connection
        $host = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbname = "courier";

        // Create connection
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);
        if ($conn->connect_error) {
            die("<div class='error'>Connection failed: " . $conn->connect_error . "</div>");
        } else {
            // Check if email already exists
            $SELECT = "SELECT email FROM employee WHERE email = ? LIMIT 1";
            $stmt = $conn->prepare($SELECT);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $rnum = $stmt->num_rows;

            if ($rnum == 0) {
                $stmt->close();

                // Insert new employee record
                $INSERT = "INSERT INTO employee (username, password, education, designation, address, phone, email) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($INSERT);
                $stmt->bind_param("sssssis", $username, $hashedPassword, $education, $designation, $address, $phone, $email);

                if ($stmt->execute()) {
                    $message = "<div class='success'>Employee Registration Successful!</div>";
                } else {
                    $message = "<div class='error'>Error in registration. Please try again!</div>";
                }
            } else {
                $message = "<div class='error'>Someone already registered using this email!</div>";
            }
            $stmt->close();
            $conn->close();
        }
    }
} else {
    $message = "<div class='error'>Invalid request method!</div>";
}

// Display the message
echo $message;
?>
    <a href="employeelog.php" class="button1">Log In</a>
    <a href="admindash.php" class="button2">Go Back</a>
</div>
</body>
</html>
