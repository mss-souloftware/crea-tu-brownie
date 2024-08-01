<?php
// Debugging info: check if script is accessed
file_put_contents('notification_log.txt', "Script accessed at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

// Log the incoming POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    file_put_contents('notification_log.txt', print_r($_POST, true), FILE_APPEND);
} else {
    file_put_contents('notification_log.txt', "No POST data received\n", FILE_APPEND);
}
?>
