<?php

function calculateTotalCost($rooms, $nights, $discounted_rate) {
    return $rooms * $nights * $discounted_rate;
}
