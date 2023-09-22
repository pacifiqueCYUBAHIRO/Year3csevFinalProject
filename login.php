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
$passwordInput = $_POST['password'];

// Query the database for the user
$sql = "SELECT * FROM employees WHERE username='$username'";
$result = mysqli_query($conn, $sql);

// Check if the user exists
if (mysqli_num_rows($result) > 0) {
	// User exists, retrieve the stored password
	$row = mysqli_fetch_assoc($result);
	$storedPassword = $row['password'];
	
	// Check if the input password matches the stored password's case
	if ($passwordInput === $storedPassword) {
		// Password case matches, set session variables and redirect to dashboard
		$_SESSION['employee_id'] = $row['employee_id'];
		$_SESSION['username'] = $row['username'];
		$_SESSION["logged-in"] = true;
		header('Location: attendance.php');
		exit;
	} else {
		// Password case doesn't match, show error message
		echo '<script>
    window.onload = function() {
        alert("Invalid password");
        window.location = "login.html";
    };
</script>';
	}
} else {
	// User doesn't exist, show error message
	echo '<script>
    window.onload = function() {
        alert("Invalid username or password");
        window.location = "login.html";
    };
</script>';
}

mysqli_close($conn);
?>