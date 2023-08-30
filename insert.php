<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $imageData = $_POST['image_data'];
    $password = $_POST['password'];

    // Establish a MySQL connection
    $mysqli = new mysqli('localhost', 'root', '', 'pacifique');

    // Check connection
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the email is already registered
    $checkEmailQuery = $mysqli->prepare("SELECT email FROM employees WHERE email = ?");
    $checkEmailQuery->bind_param("s", $email);
    $checkEmailQuery->execute();
    $checkEmailResult = $checkEmailQuery->get_result();

    if ($checkEmailResult->num_rows > 0) {
        // Email is already registered
        echo '<script>
            window.onload = function() {
                alert("Email is already registered. Please use a different email.");
                window.location = "register.html";
            };
        </script>';
        exit;
    }

    // Close the statement
    $checkEmailQuery->close();

    // Decode the image data
    $decodedImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));

    // Prepare and execute the INSERT query
    $insertStmt = $mysqli->prepare("INSERT INTO employees (fullname, username, email, department, image_data, password) VALUES (?, ?, ?, ?, ?, ?)");
    $insertStmt->bind_param("ssssss", $fullname, $username, $email, $department, $decodedImageData, $password);
    $insertStmt->execute();
    $insertStmt->close();

    // Upload image
    $uploadPath = 'uploads/' . $username . '.jpg'; // Change the extension if needed
    file_put_contents($uploadPath, $decodedImageData);

    // Redirect the user or show a success message
    echo '<script>
        window.onload = function() {
            alert("You have registered successfully!");
            window.location = "login.html";
        };
    </script>';
    exit;
}
?>
