
<?php
session_start();
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

// Get username and password from form
$username = $_POST['username'];
$password = $_POST['password'];

// Query the database for the user
$sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
$result = mysqli_query($conn, $sql);

// Check if the user exists
if (mysqli_num_rows($result) > 0) {
	// User exists, set session variables and redirect to dashboard
	$row = mysqli_fetch_assoc($result);
	$_SESSION['id'] = $row['id'];
	$_SESSION['username'] = $row['username'];
	$_SESSION["logged-in"] = true;
	header('Location: admin.php');
	exit;
} else {
	// User doesn't exist, show error message
	echo "Invalid username or password";
}

mysqli_close($conn);
?>
