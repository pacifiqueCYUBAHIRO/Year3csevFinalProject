<?php
// Retrieve the image data from the POST request
$imageData = $_POST['image_data'];

// Insert the image data and attendance details into your MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pacifique";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check the database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the current date and time
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');

// Insert the attendance details into the database (you may need to adapt this to your database schema)
$sql = "INSERT INTO attendance (date, join_time, image_data, username) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssss", $currentDate, $currentTime, $imageData, $username);

    if (mysqli_stmt_execute($stmt)) {
        echo 'Success'; // Send a success response to the JavaScript code
    } else {
        echo "Error inserting data: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
