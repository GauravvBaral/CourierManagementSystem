<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "courier";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch customer data
$query = "SELECT * FROM customers";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer View</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color:rgb(162, 162, 167);
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }

        button {
            padding: 5px 10px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    .goback-container {
        text-align: center;
        margin-top: 20px;
    }
    .goback {
        display: inline-block;
        padding: 10px 20px;
        background-color: teal;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    .goback:hover {
        background-color: darkcyan;
        transform: scale(1.05);
    }
</style>
    </style>
</head>
<body>
    <div class="container">
        <h1>Customer List</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['username'] ?></td>
                        <td><?= $row['address'] ?></td>
                        <td><?= $row['phone'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td>
                            <form method="POST" action="delete_customer.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div class="goback-container">
        <a href="admindash.php" class="goback">Go Back</a>
    </div>
</body>
</html>

<?php $conn->close(); ?>