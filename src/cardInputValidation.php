<?php

function validateCardInput($card_number, $expiry, $cvv) 
{
    if (!preg_match('/^\d{16}$/', $card_number)) {
        return "Invalid card number. Must be 16 digits.";
    } elseif (!preg_match('/^\d{2}\/\d{2}$/', $expiry)) {
        return "Invalid expiry date format. Use MM/YY.";
    } elseif (!preg_match('/^\d{3,4}$/', $cvv)) {
        return "Invalid CVV. Must be 3 or 4 digits.";
    }
    return null;
}


?>