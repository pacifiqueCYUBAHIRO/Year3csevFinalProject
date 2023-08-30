<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pacifique";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

// Get token and new password from form
$token = $_POST['token'];
$newPassword = $_POST['new_password'];

// Query the database for the user with the given token
$sql = "SELECT * FROM employees WHERE reset_token='$token'";
$result = mysqli_query($conn, $sql);

// Check if the user exists
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_assoc($result);
	$email = $row['email'];

	// Update user's password and reset token
	$updatePasswordQuery = "UPDATE employees SET password='$newPassword', reset_token=NULL, reset_token_timestamp=NULL WHERE email='$email'";
	if (mysqli_query($conn, $updatePasswordQuery)) {
		echo "Password updated successfully.";
	} else {
		echo "Error updating password: " . mysqli_error($conn);
	}
} else {
	// User doesn't exist, show error message
	echo "Invalid reset token.";
}

mysqli_close($conn);
?>
