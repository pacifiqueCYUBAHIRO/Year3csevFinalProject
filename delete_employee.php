<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pacifique";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if the request is a POST request and contains the employee_id parameter
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["employee_id"])) {
    // Get the employee_id from the request
    $employee_id = $_POST["employee_id"];

    // Delete the employee from the database
    $sql = "DELETE FROM employees WHERE employee_id='$employee_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Employee deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
