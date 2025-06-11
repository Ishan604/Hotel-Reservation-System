<?php

function deleteUserById($conn, $user_id) {
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    if ($stmt === false) {
        return 'Prepare failed: ' . $conn->error;
    }
    $stmt->bind_param('i', $user_id);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return 'Delete failed: ' . $stmt->error;
    }
}
