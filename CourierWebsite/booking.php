<?php include('bookingorder.php');

    // fetch the record to be updated
    if (isset($_GET['edit'] )) {
        $id = $_GET['edit']; 
        $edit_state = true;
        $rec = mysqli_query($db, "SELECT * FROM orders WHERE id=$id;");
        $record = mysqli_fetch_array($rec); 
        $name = $record['name'];
        $address = $record['address'];
        $phone = $record['phone'];
        $toname = $record['toname'];
        $toaddress = $record['toaddress'];
        $tophone = $record['tophone'];
        $weight = $record['weight'];
        $id = $record['id'];
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Booking Page</title>
    <link rel="stylesheet" type="text/css" href="CSS/book.css">
    <script>
        function validateForm() {
            let name = document.forms["orderForm"]["name"].value.trim();
            let address = document.forms["orderForm"]["address"].value.trim();
            let phone = document.forms["orderForm"]["phone"].value.trim();
            let weight = document.forms["orderForm"]["weight"].value.trim();
            let toname = document.forms["orderForm"]["toname"].value.trim();
            let toaddress = document.forms["orderForm"]["toaddress"].value.trim();
            let tophone = document.forms["orderForm"]["tophone"].value.trim();

            // Regex for validating phone number (only digits, 10 characters)
            let phoneRegex = /^\d{10}$/;
            // Regex for validating weight (only numbers, up to 2 decimal places)
            let weightRegex = /^\d+(\.\d{1,2})?$/;

            if (name === "" || address === "" || phone === "" || weight === "" || toname === "" || toaddress === "" || tophone === "") {
                alert("All fields must be filled out!");
                return false;
            }

            if (!phoneRegex.test(phone)) {
                alert("Please enter a valid 10-digit phone number.");
                return false;
            }

            if (!phoneRegex.test(tophone)) {
                alert("Please enter a valid 10-digit receiver's phone number.");
                return false;
            }

            if (!weightRegex.test(weight) || weight <= 0 || weight > 30) {
                alert("Please enter a valid package weight.");
                return false;
            }

            return true;
        }
    </script>
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

<h1 style="text-align: center; color: white; background-color: teal; padding: 20px;">PLACE YOUR ORDER HERE!</h1>

<section style="display: flex; justify-content: center; margin-top: 20px;">
    <div style="border: 2px solid teal; padding: 15px; background-color: #eafaf1; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); display: flex; align-items: center;">
        <h5 style="margin: 0 20px 0 0; font-size: 16px; font-weight: bold; color: teal;">NOTE TO CUSTOMERS :</h5>
        <div>
            <p style="margin: 0 0 0.1px;">The Price is Fixed, and We Accept Only Cash On Delivery! We deliver your courier within 2 to 3 working days for a minimum distance of 60km!,We can't accept package weight more than 30kgs. Below are the details for weight and prices:</p>
            <ul style="list-style: none; padding: 0; font-size: 14px; color: #333;">
                <li>1. Range: 1KG - 10KG - Price: 100Rs</li>
                <li>2. Range: 11KG - 20KG - Price: 200Rs</li>
                <li>3. Range: 21KG - 30KG - Price: 300Rs</li>
            </ul>
        </div>
    </div>
</section>

<form name="orderForm" method="post" action="bookingorder.php" onsubmit="return validateForm()">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <div class="form-wrapper">
        <div class="form-box">
            <div class="form-container">
                <!-- Your Details Table -->
                <table>
                    <tr>
                        <th colspan="2">Your Details</th>
                    </tr>
                    <tr>
                        <td>Your Full Name:</td>
                        <td><input type="text" name="name" class="input-box" value="<?php echo $name; ?>"></td>
                    </tr>
                    <tr>
                        <td>Your Complete Address:</td>
                        <td><input type="text" name="address" class="input-box" value="<?php echo $address; ?>"></td>
                    </tr>
                    <tr>
                        <td>Your Contact Number:</td>
                        <td><input type="text" name="phone" class="input-box" value="<?php echo $phone; ?>"></td>
                    </tr>
                    <tr>
                        <td>Package Weight (kg):</td>
                        <td><input type="text" name="weight" class="input-box" value="<?php echo $weight; ?>"></td>
                    </tr>
                </table>

                <!-- Receiver's Details Table -->
                <table>
                    <tr>
                        <th colspan="2">Receiver's Details</th>
                    </tr>
                    <tr>
                        <td>Receiver's Full Name:</td>
                        <td><input type="text" name="toname" class="input-box" value="<?php echo $toname; ?>"></td>
                    </tr>
                    <tr>
                        <td>Receiver's Complete Address:</td>
                        <td><input type="text" name="toaddress" class="input-box" value="<?php echo $toaddress; ?>"></td>
                    </tr>
                    <tr>
                        <td>Receiver's Contact Number:</td>
                        <td><input type="text" name="tophone" class="input-box" value="<?php echo $tophone; ?>"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="button-container">
        <?php if ($edit_state == false): ?>
            <button type="submit" name="save" class="btn">Place Order!</button>
        <?php else: ?>
            <button type="submit" name="update" class="btn">Update</button>
        <?php endif ?>  
    </div>
</form>

<div style="text-align: center; margin-top: 20px;">
    <button onclick="window.location.href='customerdash.php'" class="hover-button">Go Back</button>
</div>

</body>
</html>
