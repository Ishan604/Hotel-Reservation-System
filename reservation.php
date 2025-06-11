<?php
session_start();

if (isset($_SESSION['location'])) 
{
    $location = $_SESSION['location'];
    $checkIn = $_SESSION['checkIn'];
    $checkOut = $_SESSION['checkOut'];
    $adults = $_SESSION['adults'];
    $children = $_SESSION['children'];
    $rooms = $_SESSION['rooms'];
}

// Calculate total length of stay (number of nights)
$numNights = 0;
if (!empty($checkIn) && !empty($checkOut)) 
{
    $checkInDate = new DateTime($checkIn);
    $checkOutDate = new DateTime($checkOut);
    $interval = $checkInDate->diff($checkOutDate);
    $numNights = $interval->days;
}

// Safely read session variables into local vars
$roomtype = isset($_SESSION["Room_type"]) ? $_SESSION["Room_type"] : null;
$roomno = isset($_SESSION["Room_No"]) ? $_SESSION["Room_No"] : null;
$capacity = isset($_SESSION["capacity"]) ? $_SESSION["capacity"] : null;

// Dummy data (these should ideally come from user input or session)
$_SESSION["roomName"] = "Princely House Apartment";      
$roomAddress = "42, Humes Road, 80000 Galle, Sri Lanka";
$roomRating = "9.1 Superb · 181 reviews"; 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_reservation_system";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (isset($_POST['reserve'])) 
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $email = $_POST['email'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $occupants = $_POST['occupants'];
        $phone = $_POST['phone'];
        $country = $_POST['country'];

        $is_available = 0; // mark reserved

        if($conn)
        {
            $check_email_query = "SELECT customer_id FROM customer WHERE email='$email'";
            $result = mysqli_query($conn, $check_email_query);

            if (mysqli_num_rows($result) > 0) 
            {
                $user = mysqli_fetch_assoc($result);
                $customer_id = $user['customer_id'];
            } 
            else 
            {
                $error_message = "Email not found! Please register first.";
            }

            if (isset($customer_id)) 
            {
                // Insert reservation
                $insert_reservation = "INSERT INTO reservations (customer_id, customer_email, check_in_date, check_out_date, occupants, status) 
                                        VALUES ('$customer_id', '$email', '$checkIn', '$checkOut', '$occupants', 'pending')";
                
                if (mysqli_query($conn, $insert_reservation)) 
                {
                    $reservation_id = mysqli_insert_id($conn);

                    // Insert room details
                    $insert_room = "INSERT INTO rooms (customer_id, room_no, room_type, is_available, capacity)
                                    VALUES ('$customer_id', '$roomno', '$roomtype', '$is_available', '$capacity')";
                    
                    if (!mysqli_query($conn, $insert_room))
                    {
                        echo "Error in inserting room details: " . mysqli_error($conn);
                    }
                } 
                else 
                {
                    echo "Error in inserting reservation: " . mysqli_error($conn);
                }

                // Insert credit card if provided
                if (isset($_POST['add_credit_card']) && $_POST['add_credit_card'] === 'yes') 
                {
                    $card_number = $_POST['card_number'];
                    $expiry = $_POST['expiry'];
                    $cvv = $_POST['cvv'];

                    $insert_payment = "INSERT INTO credit_cards (customer_id, card_number, expiry, cvv) 
                                    VALUES ('$customer_id', '$card_number', '$expiry', '$cvv')";
                    
                    if (mysqli_query($conn, $insert_payment)) 
                    {
                        $update_status = "UPDATE reservations SET status='confirmed' WHERE reservation_id='$reservation_id'";
                        mysqli_query($conn, $update_status);
                    } 
                    else 
                    {
                        echo "Error in inserting payment details: " . mysqli_error($conn);
                    }
                }
            }
        }
        else
        {
            $error_message = "Connection failed!";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking.com - Complete your reservation</title>
    <link rel="stylesheet" href="Style_files/reservation_design.css">
    <style>
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #2ecc71;
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: none;
            z-index: 1000;
            animation: slideIn 0.5s forwards, fadeOut 0.5s forwards 3s;
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        
        .error-notification {
            background-color: #e74c3c;
        }
    </style>
    </head>
<body>
    
    <?php if(isset($error_message)): ?>
        <div class="notification error-notification" id="errorNotification">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <header class="booking-header">
        <div class="container">
            <div class="logo">The<span style="font-size: larger; color:#ff5a5f">Crown</span>Stays</div>
            <nav class="main-nav">
                <a href="registration.php" class="btn btn-register">Register</a>
                <a href="signin.php" class="btn btn-signin">Sign in</a>
            </nav>
        </div>
    </header>

    <main class="container reservation-content">
        <div class="left-column">
            <section class="room-details-summary">
                <div class="stars">
                    <span class="star-icon" style="color: orange">★</span>
                    <span class="star-icon" style="color: orange">★</span>
                    <span class="star-icon" style="color: orange">★</span>
                </div>
                <h2><?php echo htmlspecialchars($_SESSION["roomName"]); ?></h2>
                <p><?php echo htmlspecialchars($roomAddress); ?></p>
                <div class="rating">
                    <span class="rating-score">9.1</span>
                    <span class="rating-text">Superb · 181 reviews</span>
                </div>
                <ul class="amenities">
                    <li><img src="img/icons/wifi.png" style="width: 20px;"> WiFi</li>
                    <li><img src="img/icons/wifi.png" style="width: 20px;">Airport shuttle</li>
                    <li><img src="img/icons/wifi.png" style="width: 20px;">Parking</li>
                </ul>
            </section>

            <section class="your-booking-details">
                <h3>Your booking details</h3>
                <div class="booking-info-row">
                    <span class="label">Check-in</span>
                    <span class="value"><?php echo !empty($checkIn) ? date('D M d Y', strtotime($checkIn)) : 'N/A'; ?></span>
                    <span class="time">12:30 - 22:00</span>
                </div>
                <div class="booking-info-row">
                    <span class="label">Check-out</span>
                    <span class="value"><?php echo !empty($checkOut) ? date('D M d Y', strtotime($checkOut)) : 'N/A'; ?></span>
                    <span class="time">08:30 - 12:00</span>
                </div>
                <p class="total-length">Total length of stay: <strong><?php echo $numNights; ?> night<?php echo ($numNights > 1) ? 's' : ''; ?></strong></p>
                <p class="selected-rooms">You selected <strong><?php echo htmlspecialchars($rooms); ?> room for <?php echo htmlspecialchars($adults); ?> adult<?php echo ($adults > 1) ? 's' : ''; ?></strong></p>
                <a href="#" class="change-selection">Change your selection</a>
            </section>
        </div>

        <div class="right-column">
            <div class="highlight-message">
                <span style="color: green">If you don't have any account then create an account and if you have an account
                then login to your account
                </span>
                <div class="highlight-actions">
                    <a href="signin.php" class="highlight-link">Sign in</a> or <a href="registration.php" class="highlight-link">Create a free account</a>
                </div>
            </div>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="reservation-form">
                <h3>Enter your details</h3>
                <div class="message-box required-info">
                    Almost done! Just fill in the <span class="required-star">*</span> required info
                </div>

                <div class="form-group-row">
                    <div class="form-group">
                        <label for="firstName">First name <span class="required-star">*</span></label>
                        <input type="text" id="firstName" name="firstName" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last name <span class="required-star">*</span></label>
                        <input type="text" id="lastName" name="lastName" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email address <span class="required-star">*</span></label>
                    <input type="email" id="email" name="email" required>
                    <small>Confirmation email goes to this address</small>
                </div>

                <div class="form-group">
                    <label for="occupants">Number of Occupants <span class="required-star">*</span></label>
                    <input type="text" id="occupants" name="occupants" required>
                </div>

                <div class="form-group">
                    <label for="occupants">Country/Region <span class="required-star">*</span></label>
                    <select id="occupants" name="country" required>
                        <option value="Sri Lanka" selected>Sri Lanka</option>
                        </select>
                </div>

                <div class="form-group">
                    <label for="phone">Phone number <span class="required-star">*</span></label>
                    <div class="phone-input-group">
                        <span class="phone-prefix">LK +94</span>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                </div>

                <div class="form-group radio-group">
                    <label>Who are you booking for? (optional)</label>
                    <div>
                        <input type="radio" id="mainGuest" name="bookingFor" value="mainGuest" checked>
                        <label for="mainGuest">I am the main guest</label>
                    </div>
                    <div>
                        <input type="radio" id="someoneElse" name="bookingFor" value="someoneElse">
                        <label for="someoneElse">Booking is for someone else</label>
                    </div>
                </div>

                <div class="form-group radio-group">
                    <label>Are you travelling for work? (optional)</label>
                    <div>
                        <input type="radio" id="travelWorkYes" name="travelWork" value="yes">
                        <label for="travelWorkYes">Yes</label>
                    </div>
                    <div>
                        <input type="radio" id="travelWorkNo" name="travelWork" value="no" checked>
                        <label for="travelWorkNo">No</label>
                    </div>
                </div>

                <section class="good-to-know">
                    <h3>Good to know:</h3>
                    <ul>
                        <li>Credit card details needed</li>
                        <li>Stay flexible: You can cancel for free before <?php echo date('D M d Y', strtotime($checkIn)); ?> at 7.00 P.M</li>
                        <li>You'll get the entire apartment to yourself.</li>
                        <li>No payment needed today. You'll pay when you stay.</li>
                    </ul>
                </section>

                <section class="arrival-time">
                    <h3>Your arrival time</h3>
                    <div class="message-box success-info">
                        You can check in between 12:00 A.M and 18:00 P.M
                    </div>
                    <div class="form-group">
                        <label for="arrivalTime">Add your estimated arrival time (optional)</label>
                        <select id="arrivalTime" name="arrivalTime">
                            <option value="">Please select</option>
                            <?php
                            $start = strtotime('12:30');
                            $end = strtotime('22:00');
                            while ($start <= $end) {
                                echo '<option value="' . date('H:i', $start) . '">' . date('H:i', $start) . '</option>';
                                $start = strtotime('+30 minutes', $start);
                            }
                            ?>
                        </select>
                        <small>Time is for Galle time zone</small>
                    </div>
                </section>

                <div class="price-summary">
                    <h3>Your price summary</h3>
                    <div class="price-row">
                        <span class="price-label">Price</span>
                        <span class="price-value">US$27</span>
                    </div>
                    <small>+US$3 taxes and charges</small>
                    <div class="price-information">
                        <p>Excludes US$2.70 in taxes and charges</p>
                        <p>10 % Property service charge <span>US$2.70</span></p>
                        <a href="#" class="hide-details">Hide details</a>
                    </div>
                </div>

                <section class="payment-schedule">
                    <h3>Your payment schedule</h3>
                    <p>No payment today. You'll pay when you stay.</p>
                </section>

                <section class="cancellation-policy">
                    <h3>How much will it cost to cancel?</h3>
                    <p>Free cancellation before <?php echo date('D M d', strtotime($checkIn)); ?></p>
                    <p>From 00:00 <?php echo date('D M d', strtotime($checkIn . ' + 1 day')); ?> <span class="cancellation-fee">2000 LKR</span></p>
                </section>

                <div class="limited-supply">
                    Limited supply for your dates!
                    <p>121 apartments like this are already unavailable on our site</p>
                </div>

                <section class="apartment-features">
                    <h3>Apartment with Terrace</h3>
                    <ul>
                        <li>Breakfast included in the price</li>
                        <li>Free cancellation before <?php echo date('D M d Y', strtotime($checkIn)); ?></li>
                        <li>Guests: <?php echo htmlspecialchars($adults); ?> adult<?php echo ($adults > 1) ? 's' : ''; ?></li>
                        <li>Spotless apartment - 9.2</li>
                        <li>No smoking</li>
                    </ul>
                </section>

                <!-- Add Credit Card Details Section -->
                <div class="form-group">
                <label><span style="color: #cc0000;">*</span>Add Card Details?</label>
                <div class="radio-group">
                    <div>
                    <input type="radio" id="cc_yes" name="add_credit_card" value="yes" />
                    <label for="cc_yes">Yes</label>
                    </div>
                    <div>
                    <input type="radio" id="cc_no" name="add_credit_card" value="no" />
                    <label for="cc_no">No</label>
                    </div>
                </div>
                </div>

                <!-- Payment Access Form (Initially hidden) -->
                <div id="payment-details" style="display:none; margin-top: 15px;">
                <div class="form-group">
                    <label for="card_number">Card Number</label>
                    <input
                    type="text"
                    id="card_number"
                    name="card_number"
                    maxlength="19"
                    placeholder="1234 5678 9012 3456"
                    autocomplete="off"
                    required
                    />
                </div>
                <div class="form-group form-group-row">
                    <div style="flex:1;">
                    <label for="expiry">Expiry (MM/YY)</label>
                    <input
                        type="text"
                        id="expiry"
                        name="expiry"
                        maxlength="5"
                        placeholder="MM/YY"
                        pattern="^(0[1-9]|1[0-2])\/\d{2}$"
                        required
                    />
                    </div>
                    <div style="flex:1;">
                    <label for="cvv">CVV</label>
                    <input
                        type="password"
                        id="cvv"
                        name="cvv"
                        maxlength="4"
                        placeholder="123"
                        autocomplete="off"
                        required
                    />
                    </div>
                </div>
                </div>

                <!-- Red Warning Message for No -->
                <div
                id="cancel-warning"
                style="
                    display: none;
                    color: #cc0000;
                    font-weight: bold;
                    margin-top: 15px;
                    border: 1px solid #cc0000;
                    padding: 10px;
                    border-radius: 5px;
                    background-color: #ffebe6;
                "
                >
                The reservation will automatically cancel at 7.00 P.M.
                </div>

                <button type="submit" class="btn btn-reserve" name="reserve">Reserve</button>
            </form>
        </div>
    </main>

    <footer>
        </footer>
        <!-- Add this script at the bottom of your form or page -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const yesRadio = document.getElementById("cc_yes");
    const noRadio = document.getElementById("cc_no");
    const paymentDetails = document.getElementById("payment-details");
    const cancelWarning = document.getElementById("cancel-warning");

    function togglePaymentAccess() {
        if (yesRadio.checked) {
        paymentDetails.style.display = "block";
        cancelWarning.style.display = "none";

        // Make payment inputs required when visible
        document.getElementById("card_number").required = true;
        document.getElementById("expiry").required = true;
        document.getElementById("cvv").required = true;
      } else if (noRadio.checked) {
        paymentDetails.style.display = "none";
        cancelWarning.style.display = "block";

        // Remove required attribute when hidden
        document.getElementById("card_number").required = false;
        document.getElementById("expiry").required = false;
        document.getElementById("cvv").required = false;
      } else {
        paymentDetails.style.display = "none";
        cancelWarning.style.display = "none";

        document.getElementById("card_number").required = false;
        document.getElementById("expiry").required = false;
        document.getElementById("cvv").required = false;
      }
    }

    yesRadio.addEventListener("change", togglePaymentAccess);
    noRadio.addEventListener("change", togglePaymentAccess);

    if(errorNotification) 
    {
        errorNotification.style.display = 'block';
        setTimeout(() => { errorNotification.style.display = 'none'; }, 3500);
    }

    // Initialize on load in case of pre-filled values
    togglePaymentAccess();
  });
</script>
</body>
</html>