<?php
session_start();
// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();
// Check if user is logged in
if (!isset($_SESSION['id'])) {
	header('Location: login.html');
	exit;
}

// Prevent caching of this page
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
?>
