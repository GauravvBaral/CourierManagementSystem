<?php
$message = ""; // Variable to store success or error messages
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password']; // Plain text password from the form
    $address = htmlspecialchars($_POST['address']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Validate inputs
    if (empty($username) || empty($password) || empty($address) || empty($phone) || empty($email)) {
        $message = "<strong style='color:red;'>All fields are required!</strong>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<strong style='color:red;'>Invalid email format!</strong>";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $message = "<strong style='color:red;'>Phone number must be 10 digits!</strong>";
    } elseif (strlen($password) < 8) {
        $message = "<strong style='color:red;'>Password must be at least 8 characters long!</strong>";
    } else {
        $host = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbname = "courier";

        // Create connection
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);
        if ($conn->connect_error) {
            die('Connection Failed: ' . $conn->connect_error);
        } else {
            $SELECT = "SELECT email FROM customers WHERE email = ? LIMIT 1";
            $INSERT = "INSERT INTO customers (username, password, address, phone, email) VALUES (?, ?, ?, ?, ?)";

            // Prepare statement
            $stmt = $conn->prepare($SELECT);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $rnum = $stmt->num_rows;

            if ($rnum == 0) {
                $stmt->close();

                // Hash the password before storing it
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare($INSERT);
                $stmt->bind_param("sssis", $username, $hashedPassword, $address, $phone, $email);
                if ($stmt->execute()) {
                    $message = "<strong style='color:green;'>Customer Registration is Successful!</strong>";
                } else {
                    $message = "<strong style='color:red;'>Error in Registration. Please try again!</strong>";
                }
            } else {
                $message = "<strong style='color:darkblue;'>Someone Already Registered Using This Email!</strong>";
            }
            $stmt->close();
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Process</title>
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
            border: 2px solid rgba(114, 126, 236, 0.8); /* Visible border */
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
            background-color: rgb(79, 96, 242);
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
            width: 140px; /* Set fixed width for both buttons */
            text-align: center; /* Ensure text is centered within the button */
        }
        .button1:hover, .button2:hover {
            background-color: rgb(64, 19, 201);
            transform: scale(1.05);
        }
        .message {
            margin-bottom: 20px;
            font-size: 18px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Customer Registration</h1>
    <?php if (!empty($message)) { ?>
        <div class="message"><?php echo $message; ?></div>
    <?php } ?>
    <a href="customerlog.php" class="button1">Log In</a>
    <a href="index.html" class="button2">Go Back</a>
</div>
</body>
</html>