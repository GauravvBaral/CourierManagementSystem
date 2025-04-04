<?php include('bookingorder.php');
$edit_state = false;

if (isset($_GET['edit'])) {
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

</head>
<script>
    function validateForm() {
        const toname = document.forms["orderForm"]["toname"].value.trim();
        const toaddress = document.forms["orderForm"]["toaddress"].value.trim();
        const tophone = document.forms["orderForm"]["tophone"].value.trim();
        const weight = document.forms["orderForm"]["weight"].value.trim();

        if (toname === "" || toaddress === "" || tophone === "" || weight === "") {
            alert("All fields must be filled out.");
            return false;
        }

        if (!/^\d{10}$/.test(tophone)) {
            alert("Please enter a valid 10-digit contact number.");
            return false;
        }

        const weightValue = parseFloat(weight);
        if (isNaN(weightValue) || weightValue < 1 || weightValue > 40) {
            alert("Weight must be a number between 1 and 40 kilograms.");
            return false;
        }

        return true;
    }
</script>

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
                <p style="margin: 0 0 0.1px;">The Price is Fixed, and We Accept Only Cash On Delivery! We deliver your courier within 2 to 3 working days for a minimum distance of 60km!,We can't accept package weight more than 40kgs. Below are the details for weight and prices:</p>
                <ul style="list-style: none; padding: 0; font-size: 14px; color: #333;">
                    <li>1. Range: 1KG - 10KG - Price: 100Rs</li>
                    <li>2. Range: 11KG - 20KG - Price: 200Rs</li>
                    <li>3. Range: 21KG - 30KG - Price: 300Rs</li>
                </ul>
            </div>
        </div>
    </section>

    <form name="orderForm" method="post" onsubmit="return validateForm()">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="form-wrapper">
            <div class="form-box">
                <div class="form-container">
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
                        <tr>
                            <td>Package Weight (kg):</td>
                            <td><input type="text" name="weight" class="input-box" value="<?php echo $weight; ?>"></td>
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
