<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['image_captured']) && $_POST['image_captured'] === 'true') {
        $_SESSION['image_captured'] = true;
        echo 'Image capture flag set successfully';
    } else {
        echo 'Invalid request';
    }
} else {
    echo 'Invalid request method';
}
?>
