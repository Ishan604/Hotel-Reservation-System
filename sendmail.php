<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try
{
    $mail->isMail(); // Use the mail() function for sending emails
    $mail->setFrom('ishanpathirana122133@gmail.com','The Crown Stays'); // Set email parameters

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hotel_reservation_system";

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) 
    {
        echo "Connection error!".mysqli_connect_error($conn);
    }
    else
    {
    
        $current_date = date("Y-m-d"); //get current date in Y-m-d format
        $current_time = date("H:i"); //get current time in H:i format

        if($current_time == "18:00")
        {
            $query = "SELECT customer_email, reservation_id FROM reservations WHERE status='pending' AND check_in_date='$current_date'";
            $result = mysqli_query($conn, $query);

            if(mysqli_num_rows($result) > 0)
            {
                while($row = mysqli_fetch_assoc($result))
                {
                    $email = $row['customer_email'];
                    $reservation_id = $row['reservation_id'];

                    $mail->addAddress($email); // Add a recipient
                    $mail->Subject = 'Reservation Reminder'; // Set email subject
                    $mail->Body    = "Dear Customer, this is a reminder for your reservation with ID: {$reservation_id}. Please confirm your reservation.";
                    
                    // Send the email
                    if(!$mail->send())
                    {
                        echo "Message could not be sent to {$email}. Mailer Error: {$mail->ErrorInfo}";
                    }
                    else
                    {
                        echo "Message has been sent to {$email} successfully.";
                    }
                    
                    // Clear all recipients for the next iteration
                    $mail->clearAddresses();
                }
            }
            else
            {
                echo "No pending reservations found.";
            }
        }
        else
        {
            echo "Current time is not 18:00. No emails sent.";
        }
    }
}
catch(Exception $e)
{
    // Handle error
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}


?>