<?php

function validateCustomerInputs($name, $email, $checkin) {
    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "âŒ Please enter valid name and email.";
    } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $checkin)) {
        return "âŒ Invalid check-in date format.";
    }
    return null;  // No errors
}
