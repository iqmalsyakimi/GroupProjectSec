    <?php

    session_start();

    include('connect_to_db');

    $port = 3306;

    if(isset($_SESSION['package_name'])) {
        $package_id = mysqli_real_escape_string($conn, $_GET['PACKAGE_ID']);
        $package_name = $_SESSION['package_name'];
        $package_date = $_SESSION['package_date'];
        $package_duration = $_SESSION['package_duration'];
        $package_price = $_SESSION['package_price'];
    } else {
        echo "Package data not found.";
        exit;
    }

    // Extract duration's first character and turn it into a number
    $duration_number = intval(substr($package_duration, 0, 1)) + 1;

    // Add the duration to the package date to calculate the return date
    $start_date = new DateTime($package_date);
    $start_date->add(new DateInterval("P{$duration_number}D")); // Add duration as days
    $return_date = $start_date->format('Y-m-d');
    
    if(isset($_POST['submit'])) {
        
        $booking_date = date('Y-m-d');
        $package_id = mysqli_real_escape_string($conn, $_POST['package_id']);
        $pax = $_POST['pax'];
        $depart_date = mysqli_real_escape_string($conn, $_POST['depart_date']);
        $return_date = mysqli_real_escape_string($conn, $_POST['return_date']);
        $totalprice = $pax * $package_price;
        echo "$totalprice";
        $userid = $_SESSION['user_id'];
        

        $query = "INSERT INTO booking (BOOKING_DATE, PACKAGE_ID, PAX, DEPARTURE_DATE, RETURN_DATE, TOTAL_PRICE, USER_ID)
                  VALUES ('$booking_date', '$package_id', '$pax', '$depart_date', '$return_date', '$totalprice', '$userid')";

        if (mysqli_query($conn, $insertBookingQuery)) {
        // Get the auto-generated booking_id from the last inserted record
        $bookingId = mysqli_insert_id($conn) ; // Get the last inserted booking_id

        // Store the booking_id in the session
        $_SESSION['booking_id'] = $bookingId ;
    }
    }
    ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Travel Booking Form</title>
          <link rel="stylesheet" href="style.css">
        </head>
        <body>

            <div class="background">
                <div class="booking-form">

                    <h2>Travel Booking Form</h2>
                    <form action="" method="POST">
                        
                        <input type="hidden" name="package_id" value="<?php echo $package_id; ?>">
                        <label for="Package">Package:</label>
                        <input type="text" readonly name="package" id="package" value="<?php echo $_SESSION['package_name']; ?>" required>

                        <label for="pax">Pax:</label>
                        <input type="number" name="pax" id="pax" required>
                   
                        <label for="depart_date">Departure Date:</label>
                        <input type="date" readonly name="depart_date" id="depart_date" value="<?php echo $_SESSION['package_date']; ?>" required >
                       
                        <label for="return_date">Return Date:</label>
                        <input type="date" readonly name="return_date" id="return_date" value="<?php echo $return_date ?>" >

                        <a href="http://localhost/week8/payment/index.html">
                            <button type="submit" name="submit">Confirmation Booking</button>
                        </a>

                        
                    </form>
                </div>
            </div>
        </body>
        </html>